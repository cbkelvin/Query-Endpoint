<?php
use App\Services\Cafes\SearchCafes;
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CafesController extends Controller
{
    public function search(Request $request)
    {
    	$searchCafes = new SearchCafes($request->all());
    	$cafes = $searchCafes->search();

    	return response()->json( $cafes );
    }
  
}
