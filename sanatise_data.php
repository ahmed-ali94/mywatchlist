
<?php 
    function sanatise_input($data) // function that sanitises data for securty purposes/code injection.
    {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>