<!DOCTYPE html>
<html>

<title>Admin</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif;}
body, html {
    height: 100%;
    color: #777;
    line-height: 1.8;
}

/* Create a Parallax Effect */
.bgimg-1{
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}

/* First image (Logo. Full height) */
.bgimg-1 {
    background-image: url('../images/ewu2.jpg');
    min-height: 100%;
    image-rendering: auto;
  image-rendering: crisp-edges;
  image-rendering: pixelated;
}

.w3-wide {letter-spacing: 10px;}
.w3-hover-opacity {cursor: pointer;}

/* Turn off parallax scrolling for tablets and phones */
@media only screen and (max-device-width: 1600px) {
    .bgimg-1 {
        background-attachment: scroll;
        min-height: 250px;
    }
}

.facts li{
    display:inline;
    margin: 6px;
}
.links li{
    display:inline;
    margin:20px;
}
.contacts li
{
    display:inline;
}
.facts li a:hover {
    text-decoration: underline;
}

.row-links
{
    padding:10px;
}
.row-links .logos
{
    margin-left: 100px;
    float:left;
}
.row-links ul.left
{
    margin-left:400px;
    margin-top:5px;
    padding: 0px;
    width: 150px;
}
.row-links ul.middle
{
    margin-left:600px;
    margin-top:-93px;
    padding: 0px;
    width: 150px;
}
.row-links ul.right
{
    margin-left:800px;
    margin-top:-93px;
    padding: 0px;
    width: 150px;

}
.row-links ul.farright
{
    margin-left:1000px;
    margin-top:-93px;
    padding: 0px;
    width: 150px;
}
ul.contacts li.a:hover, li.b:hover
{
    text-decoration: underline;
}
.row-links ul.left li.a:hover, li.b:hover, li.c:hover
{
    text-decoration: underline;
}
.row-links ul.right li.a:hover, li.b:hover, li.c:hover
{
    text-decoration: underline;
}
.row-links ul.middle li.a:hover, li.b:hover, li.c:hover
{
    text-decoration: underline;
}
.row-links ul.farright li.a:hover, li.b:hover, li.c:hover
{
    text-decoration: underline;
}
ul.facts li.priv:hover, li.access:hover, li.rules:hover
{
    text-decoration:underline;
}

.fa.fa-facebook:hover
{
    background-color:#3b5998;;
    border-color:#3b5998;
}
.fa.fa-twitter:hover
{
    background-color:#4099ff;
    border-color:#4099ff;
}
.fa.fa-youtube-play:hover
{
    background-color:red;
    border-color:red;
}
.fa.fa-instagram:hover
{
    background-color:#e4405f;
    border-color:#e4405f;
}
.fa.fa-linkedin:hover
{
    background-color:#0077b5;
    border-color:#0077b5;
}
.social-media i.fa
{
    display:inline-block;
    border: 3px solid white;
    border-radius:50%;
    width:42px;
    height:42px;
    text-align:center;
    margin:6px;
  padding:5px;

}
</style>
<body>

<!-- Navbar (sit on top) -->

<!-- First Parallax Image with Logo Text -->
<form method="POST">
    <header class="w3-display-container w3-content w3-center" style="max-width:1500px">
<div class="bgimg-1 w3-display-container" id="home">
  <div class="w3-display-middle" style="white-space:nowrap;">
    <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">CSTEM <span class="w3-hide-small">UNDERGRADUATE RESEARCH</span> GRANT</span>
  </div>
</div>
</header>
<!-- Container (About Section) -->
<div class="w3-content w3-container w3-padding-32" id="about">
  <h3 class="w3-center">You are not an admin for the CSTEM Undergraduate Research Grant.</h3>
  <p></p>
 </div>
 
  <div class="w3-content w3-container w3-padding-8" id="apply">
  </div>
  <div style="text-align: center;">
  <a href="../index.php" class="w3-button w3-grey w3-round w3-large" id="student">Back To Home Page</a>
  <br>
  <br>
  </div>
  <!-- Footer -->
<!--<footer class="w3-center w3-black w3-padding-64 w3-opacity ">
  <a href="#home" class="w3-button w3-light-grey"><i class="fa fa-arrow-up w3-margin-right"></i>To the top</a>
</footer>-->
<footer class="foot w3-center w3-dark-grey w3-padding-32">
    <div class="social-media">
        <a href="https://www.facebook.com/ewueagles/"><i class="fa fa-facebook" style="font-size: 1.80em"></i></a> 
        <a href="https://twitter.com/ewueagles"><i class="fa fa-twitter" style="font-size: 1.80em"></i></a> 
        <a href="https://www.instagram.com/easternwashingtonuniversity/"><i class="fa fa-instagram" style="font-size: 1.7em"></i></a> 
        <a href="https://www.youtube.com/user/ewuvideo"><i class="fa fa-youtube-play" style="font-size: 1.6em"></i></a> 
        <a href="https://www.linkedin.com/school/eastern-washington-university/"><i class="fa fa-linkedin" style="font-size: 1.7em"></i></a>
    </div>
    <div class="row-links">
        <div class="logos">
            <a class="logo" href="https://www.ewu.edu"><img src="../images/footer-logo.png" alt="Eastern Washington University"></a><br>
            <ul class="contacts"><li class="a"><a href="tel:15093596200" style="text-decoration:none;">509.359.6200</a></li>  • <li class="b"> <a href="https://www.ewu.edu/contact-ewu" style="text-decoration:none;">Contact Information</a></li></ul><br>
        </div>
        
            <ul class="left" style="list-style-type:none">
                <li class="a"><a href="https://www.ewu.edu/about/" style="text-decoration: none;">About EWU </a></li>
                <li class="b"><a href="https://www.ewu.edu/apply/visit-ewu/" style="text-decoration: none;">Visit EWU</a></li>
                <li class="c"><a href="https://www.ewu.edu/academics/#section-5" style="text-decoration: none;">Campus Locations</a></li>
            </ul>
            <ul class="middle" style="list-style-type:none">
                <li class="a"><a href="https://sites.ewu.edu/foundation/" style="text-decoration: none;">EWU Foundation</a></li>
                <li class="b"><a href="https://sites.ewu.edu/diversityandinclusion/" style="text-decoration: none;">Diversity</a></li>
                <li class="c"><a href="https://sites.ewu.edu/hr/apply-for-jobs/" style="text-decoration: none;">Jobs</a></li>
            </ul>
            <ul class="right" style="list-style-type:none">
                <li class="a"><a href="https://www.ewu.edu/about/leadership/"style="text-decoration: none;">Administration</a></li>
                <li class="b"><a href="https://www2.ewu.edu/library"style="text-decoration: none;">EWU Libraries</a></li>
                <li class="c"><a href="https://www2.ewu.edu/site-map?filter=a"style="text-decoration: none;">Site Map</a></li>
            </ul>
            <ul class="farright" style="list-style-type:none">
                <li class="a"><a href="https://sites.ewu.edu/instructional-technology/learning-management-system"style="text-decoration: none;">Canvas</a></li>
                <li class="b"><a href="https://eaglenet.ewu.edu/"style="text-decoration: none;">EagleNET</a></li>
                <li class="c"><a href="index.php?id=admin"style="text-decoration: none;">Admin Login</a></li>
            </ul>
    </div>
<br>
    <ul class="facts" >
            <li class="list-inline-item text-muted">&copy; 2018 Eastern Washington University</li> | 
            <li class="priv"><a href="https://www.ewu.edu/privacy-policy" style="text-decoration: none;">Privacy Policy</a></li> | 
            <li class="access"><a href="https://www.ewu.edu/accessibility" style="text-decoration: none;">Accessibility</a></li> | 
            <li class="rules"><a href="https://sites.ewu.edu/policies/" style="text-decoration: none;">Rules and Policies</a></li>
          </ul>    


</footer>
</form>
  
   


</body>
</html>
