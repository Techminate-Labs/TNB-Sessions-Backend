<?php

namespace App\Services\Event;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Event\EventValidation;

//Models
use App\Models\Review;

class ReviewServices extends BaseServices{
    private $reviewModel = Review::class;

    public function reviewList($request){
        $this->logCreate($request);
        if ($request->has('q')){
            $reviews = $this->filterRI->filterBy1PropPaginated($this->reviewModel, $request->q, $request->limit, 'name');
        }else{
            $reviews = $this->baseRI->listWithPagination($this->reviewModel, $request->limit);
        }
        if($reviews){
            return $reviews->through(function($review){
                return reviewFormat::formatreviewList($review);
            });
        }else{
            return response(["message"=>'review not found'],404);
        }
    }

    public function reviewGetById($request, $id){
        $this->logCreate($request);
        $review = $this->baseRI->findById($this->reviewModel, $id);
        if($review){
            return $review;
        }else{
            return response(["message"=>'review not found'],404);
        }
    }

    public function reviewCreate($request){
        $this->logCreate($request);
        $request->validate([
            'name'=>'required',
            'permissions'=>'required'
        ]);
        $data = $request->all();

        $review = $this->baseRI->storeInDB($this->reviewModel, $data);
        return response($review,201);
    }

    public function reviewUpdate($request, $id){
        $this->logCreate($request);
        $request->validate([ 
            'name'=>'required',
            'permissions'=>'required'
        ]);
        $review = $this->baseRI->findById($this->reviewModel, $id);
        if($review){
            $data = $request->all();
            $review->update($data);
            return response($review,201);
        }else{
            return response(["message"=>'review not found'],404);
        }
    }

    public function reviewDelete($request, $id){
        $this->logCreate($request);
        $review = $this->baseRI->findById($this->reviewModel, $id);
        if($review){
            $review->delete();
            return response(["message"=>'Delete Successfull'],200);
        }else{
            return response(["message"=>'review not found'],404);
        }
    }

}
