<?php

namespace App\Helpers;
use App\Models\Category;
class Helper
{
    public static function timeAgo($timeago)
    {
        $date = \Carbon\Carbon::parse($timeago);
        $humanReadableTime = $date->diffForHumans();
        return $humanReadableTime;
    }

    //merging new category with db voc_json columns
    public static function categories_voc_json($db_voc_json,$id)
    {
        // $getname = Category::where('id',$id)->get();
        // $cat_id = $getname[0]->id;
        // $categories = Category::where('parent_id',$cat_id)->get('name');
        // foreach ($categories as $key=>$value) {
        //     $catname = str_replace(" ", "_", $value->name);
        //     $cat_array[] = strtolower($catname);
        // }

        // $jsonkeys = array_keys($db_voc_json);
        // $mergedArray = array_merge($cat_array, $jsonkeys);
        // $uniqueArray = array_unique($mergedArray);
        // if (!empty(array_diff($jsonkeys, $uniqueArray)) || !empty(array_diff($uniqueArray, $jsonkeys))) {
        //     $result = array_unique(array_merge(
        //             array_diff($jsonkeys, $uniqueArray), 
        //             array_diff($uniqueArray, $jsonkeys)
        //         ));
        //         $voc_json = $db_voc_json;
        //         //putting into array
        //         foreach($result as $r){
        //             $voc_json[$r] = null;
        //         }
        // } else {
        //         $voc_json = $db_voc_json;
        // }
        // return $voc_json;

        $getname = Category::where('id', $id)->first();

        $cat_id = $getname->id;
        $categories = Category::where('parent_id', $cat_id)->pluck('name');

        // Initialize new category array
        $newCatArray = [];
        foreach ($categories as $name) {
            $newCatArray[strtolower(str_replace(" ", "_", $name))] = null; // Set null or a default value
        }

        // Fetch existing JSON data
        $existingJson = $db_voc_json;
        $existingKeys = array_keys($existingJson);

        // Determine keys to add or remove
        $keysToAdd = array_diff(array_keys($newCatArray), $existingKeys);
        $keysToRemove = array_diff($existingKeys, array_keys($newCatArray));

        // Update the existing JSON structure
        foreach ($keysToAdd as $key) {
            $existingJson[$key] = null; // Add new keys
        }

        foreach ($keysToRemove as $key) {
            unset($existingJson[$key]); // Remove deleted keys
        }
        return  $existingJson;
    }

    // function for number_format for easy to use
    public static function numberFormat($value)
    {
        return number_format($value,2,'.','');
    }
}