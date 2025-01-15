<template>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="style.css">
    <title>Glassmorphism Left Sided Navigation bar | HTML And CSS</title>
</head>
<body>
    <div class="navbar">
        <div class="profile">
            <div class="imgbox">
                <img src="" alt="">
            </div>
            <div class="heading">
                <h3 class="title">Webkit Coding</h3>
                <h4 class="label">Developer</h4>
            </div>
        </div>
        <!-- navitems -->
        <ul>
            <li>
                <a href="#"><span>dashboard</span></a>
            </li>
            <li>
                <a href="#"><span>dashboard</span></a>
            </li>
            <li>
                <a href="#"><span>dashboard</span></a>
            </li>
            <li>
                <a href="#"><span>dashboard</span></a>
            </li>
            <li>
                <a href="#"><span>dashboard</span></a>
            </li>
            <li>
                <a href="#"><span>dashboard</span></a>
            </li>
            <li>
                <a href="#"><span>dashboard</span></a>
            </li>
        </ul>
    </div>
</body>
</html>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap');

*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'poppins';
}
body{
    min-height: 100vh;
    background: #222;
    overflow: hidden;
}
.navbar{
    position: relative;
    background: rgba(254,254,254,0.18);
    padding: 20px;
    width: 270px;
    top: 50px;
    left: 20px;
    border: 2px solid rgba(254,254,254,0.5);
    border-radius: 15px;
}
.profile{
    position: relative;
    display: flex;
    width: 100%;
    height: 100%;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
}
.profile::after{
    position: absolute;
    content: '';
    width: 100%;
    height: 2px;
    background: #fff;
    opacity: 0.5;
    left: 0;
    bottom: -20px;
}
.profile .imgbox{
    position: relative;
    height: 80px;
    width: 80px;
    border: 2px solid #fff;
    border-radius: 50%;
    overflow: hidden;
}
.imgbox img{
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.heading{
    color: #fff;
}
.heading h3{
    font-size: 1.15em;
    font-weight: 500;
}
.heading h4{
    opacity: 0.5;
    font-size: 1em;
    font-weight: 400;
}

/*nav*/
ul{
    margin-top: 40px;
}
ul li{
    list-style: none;
}
ul li a{
    color: #fff;
    font-size: 1.2em;
    font-weight: 400;
    display: block;
    padding: 0 30px;
    height: 60px;
    line-height: 60px;
    text-decoration: none;
    text-transform: capitalize;
    border-radius: 8px;
    transition: .4s .05s ease-out;
}
ul li:hover a{
    color: #333;
    background: #fff;
    transition: 0s ease-out;
}

ul li a i{
    scale: 1.3;
    display: inline-block;
    margin-right: 20px;
}

/* now make it responsive */
@media screen and (max-width: 768px){
    .navbar{
        width: 90px;
        padding: 10px;
        transition: 0.3s 0s ease-out;
    }
    .profile{
    display: grid;
    place-content: center;
    padding-bottom: 20px;
    scale: .8;
}
.heading{
    display: none;
}
ul li a{
    text-align: center;
    padding: 0;
}
ul li a span{
    display: none;
}
ul li a i{
    margin: 0;
}
ul li{
    position: relative;
}
ul li::before{
    position: absolute;
    content: 'dashboard';
}
}
</style>