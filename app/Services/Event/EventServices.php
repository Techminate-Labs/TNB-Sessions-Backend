<?php

namespace App\Services\Item;

use Illuminate\Support\Str;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Item\EventValidation;

//Models
use App\Models\Event;

class EventServices extends BaseServices{
    
    private  $eventModel = Event::class;

    public function list($request){
        $countObj = 'session';
        $prop1 = 'title';
        if ($request->has('q')){
            $event = $this->filterRI->filterBy1PropWithCount($this->eventModel, $request->q, $request->limit, $countObj, $prop1);
        }else{
            $event = $this->baseRI->listwithCount($this->eventModel, $request->limit, $countObj);
        }
        return $event;
    }

    public function getById($id){
        $event = $this->baseRI->findById($this->eventModel, $id);
        if($event){
            return $event;
        }else{
            return response(["failed"=>'event not found'],404);
        }
    }

    public function create($request){
        $fields = EventValidation::validate1($request);
        $event = $this->baseRI->storeInDB(
            $this->eventModel,
            [
                'name' => $fields['name'],
                'slug' => Str::slug($fields['name'])
            ]
        );

        if($event){
            return response($event,201);
        }else{
            return response(["failed"=>'Server Error'],500);
        }
    }

    public function update($request, $id){
        $event = $this->baseRI->findById($this->eventModel, $id);
        if($event){
            $data = $request->all();
            if($event->name==$data['name']){
                $fields = EventValidation::validate2($request);
            }
            else{
                $fields = EventValidation::validate1($request);
            }
            $data['slug'] = Str::slug($fields['name']);
            $event->update($data);
            return response($event,201);
        }else{
            return response(["failed"=>'event not found'],404);
        }
    }

    public function delete($id){
        $event = $this->baseRI->findById($this->eventModel, $id);
        if($event){
            $event->delete();
            return response(["done"=>'event Deleted Successfully'],200);
        }else{
            return response(["failed"=>'event not found'],404);
        }
    }
}