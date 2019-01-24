<?php
    function create_form_input($name, $type, $label = '', $errors = array(), $options = array()){
        
        //Check for and process the value
        // Assume no value already exists:
        $value = false;

        // Check for a value in POST:
        if(isset($_POST[$name])) {
            $value = $_POST[$name];
        }

        // Strip slashes if Magic Quotes is enabled:
        if($value && get_magic_quotes_gpc()){
            $value = stripslashes($value);
        }

        // Start the DIV:
        echo '<div class="form-group';
        
        // Add a class if an error exists:
        if(array_key_exists($name, $errors)){ 
            echo ' has-error';
        }

        //Complete the div
        echo '">';
        
        //Create label if one is provided:
        if(!empty($label)) {
            echo '<label for="' . $name . '" class="control-label">' .$label . '</label>';
        }

        //Create a conditional that checks the input type
        if (($type === 'text') || ($type === 'password') || ($type === 'email')){
            
            //Start creating the input
            echo '<input type="' . $type . '" name="' . $name . '" id="' . $name . '" class="form-control"';

            //Add the input's value, if applicable
            if($value) echo ' value="' . htmlspecialchars($value) . '"';

            //Check for additional options
            if(!empty($options) && is_array($options)){
                foreach ($options as $k => $v){
                    echo "$k=\"$v\"";
                }
            }
            
            //Complete the element
            echo '>';
            
            //Show the error message if one exists
            if(array_key_exists($name, $errors)){
                echo '<span class="help-block">' . $errors[$name] . '</span>';
            }
        
        //Check if the input type is a textarea
        }elseif($type === 'textarea'){
            
            //Display the error first
            if(array_key_exists($name, $errors)){
                echo '<span class="help-block">' . $errors[$name] . '</span>';
            }

            //Start creating the textarea
            echo '<textarea name="' . $name . '" id="' . $name . '" class="form-control"';

            //Check for any additional options
            if(!empty($options) && is_array($options)){
                foreach($options as $k => $v){
                    echo " $k=\"$v\"";
                }
            }
            echo'>';//Complete opening tag

            //Add the value to the textarea
            if($value){
                echo $value;
            }

            echo '</textarea>';//Complete the textarea
        }//End of the primary elseif

        echo '</div>';
    }//End of the create_form_input() function