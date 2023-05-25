<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fun Park, le plus fun des parcs d'attractions">
    <title><?php echo $pageTitle . " | Fun Park" ?></title>
    <link rel="icon" type="image/svg" href="Design/picture/logo2.png">
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.loader').hide();

            $('#search').keyup(function() { //select la div search et écoute le clavier
                $field = $(this);
                $('#result').html(''); // on initialise avec une chaine vide

                if ($field.val().length >= 1) { // on affiche à partir du 1 caractère taper par l'utilisateur 
                    $.ajax({ // permet de faire de l'ajax
                        type: 'POST',
                        url: 'search.php',
                        data: 'search=' + $('#search').val(), // les données que l'on va envoyer

                        beforeSend: function() {
                            $('.loader').stop().fadeIn(); // pour faire apparaitre le chargement
                        },

                        success: function(data) {
                            $('.loader').fadeOut(); // on cache le chargement
                            $('#result').html(data); // on affiche le resultat
                        }
                    });
                }
            });
        });
    </script>