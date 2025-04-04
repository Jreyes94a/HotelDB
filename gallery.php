<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Variety Gallery</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Domine:wght@400..700&display=swap" rel="stylesheet">
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="gallery.php">Gallery</a></li>
        </ul>
    </nav>
</head>
<body>

    <h1 class="title">The Variety Hotel</h1>

    <div class="gallery">
        <img src="lobby.jpg" alt="Image 1">
        <img src="hallway.jpg" alt="Image 2">
        <img src="room.jpg" alt="Image 3">
        <img src="deck.jpg" alt="Image 4">
        <img src="doubleroom.jpg" alt="Image 5">
        <img src="lobby2.jpg" alt="Image 6">
        <img src="cityview.jpg" alt="Image 7">

    </div>

    <Style>

a {
    font font-family:"Domine", serif;
    text-decoration: none;
    align: center;
    color: #fff;
    background-color: black;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

nav ul {
            font font-family:"Domine", serif;
            list-style: none;
            padding: 0;
            margin: 20px auto;
            margin: 20px 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }    
        



        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .gallery img {
            width: 500px;
            height: 500px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .gallery img:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
        .gallery img:active {
            transform: scale(0.95);
            transition: transform 0.1s ease;
        }
        h1{
            font-size: 6.0em;
            text-align: center;
            margin: 0;
            padding: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            font-family: "Satisfy", cursive;
            font-style: normal;
        }

</body>
</html>