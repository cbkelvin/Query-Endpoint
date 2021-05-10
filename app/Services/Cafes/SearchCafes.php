<?php

namespace App\Services\Cafes;

use App\Models\Cafe;
use Auth;

class SearchCafes {
    private $query;
    private $take;
    private $orderBy;
    private $orderDirection;
    private $query;
    private $search;
    private $brewMethods;


    public function __construct( $parameters )
    {
        $this->setLocalParameters( $parameters );
        $this->query = Cafe::query();
    }

    private function setLocalParameters( $parameters )
    {
		$this->take = isset( $parameters['take'] ) ? $parameters['take'] : 6;
		$this->orderBy = isset( $parameters['order_by'] ) ? $parameters['order_by'] : 'created_at';
		$this->orderDirection = isset( $parameters['order_direction'] ) ? $parameters['order_direction'] : 'DESC';
		$this->search = isset( $parameters['search'] ) ? $parameters['search'] : '';
		$this->brewMethods = isset( $parameters['brew_methods'] ) ? $parameters['brew_methods'] : '';
    }

    

    public function search()
    {
    	$this->applySearch();
    	$this->applyBrewMethodsFilter();
    	$this->applyOrder();

        $cafes = $this->query->with('company')->paginate( $this->take );

        return $cafes;
    }

    private function applyOrder()
    {
        // $this->query->orderBy( $this->orderBy, $this->orderDirection );
         switch( $this->orderBy ){
            case 'likes':
                $this->query->withCount('likes as liked')
                     ->orderByRaw('liked DESC');
            break;
            default:
                $this->query->orderBy( $this->orderBy, $this->orderDirection );
            break;
        
    }

     public function applySearch()
    {
        if( $this->search != '' ){
            $search = urldecode( $this->search );

            $this->query->where(function( $query ) use ( $search ){
                $query->where('location_name', 'LIKE', '%'.$search.'%')
                      ->orWhere('address', 'LIKE', '%'.$search.'%')
                      ->orWhere('city', 'LIKE', '%'.$search.'%')
                      ->orWhere('state', 'LIKE', '%'.$search.'%')
                      ->orWhereHas('company', function( $query ) use ( $search ){
                            $query->where( 'name', 'LIKE', '%'.$search.'%' );
                        });
            });
        }
    }

    public function applyBrewMethodsFilter()
	{
	    if( $this->brewMethods != '' ){
	        $brewMethodIDs = explode( ',', urldecode( $this->brewMethods ) );
	        
	        $this->query->whereHas('brewMethods', function( $query ) use ( $brewMethodIDs ){
	            $query->whereIn( 'id', $brewMethodIDs );
	        });
	    }
	}

}