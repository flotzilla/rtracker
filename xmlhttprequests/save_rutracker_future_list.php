<?php


$save_er = $rt->save_future_list($flist);
        if(count($save_er) > 0){
            $errors = '';
            foreach($save_er as $err){
                $errors .=  "<h4>" . $err . "</h4><br>";
            }
        }else{
            echo "<h4>Succesfully saved</h4>";
        }