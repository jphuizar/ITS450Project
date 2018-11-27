<?php

if (in_array ('curl', get_loaded_extensions())){
	echo "CURL is available";
}
else{
	echo "curl not available";
}
?>
