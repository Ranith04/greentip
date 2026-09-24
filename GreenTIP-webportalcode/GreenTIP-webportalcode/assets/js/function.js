function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}
// slider
$('.carousel').carousel({
    interval: 3000,
    pause: false
})

// ---------------------------
$(document).ready(function(){

    jQuery(function($) {

        // settings
        var $slider = $('.slider'); // class or id of carousel slider
        var $slide = 'li'; // could also use 'img' if you're not using a ul
        var $transition_time = 1000; // 1 second
        var $time_between_slides = 4000; // 4 seconds

        function slides(){
            return $slider.find($slide);
        }

        slides().fadeOut();

        // set active classes
        slides().first().addClass('active');
        slides().first().fadeIn($transition_time);

        // auto scroll
        $interval = setInterval(
            function(){
                var $i = $slider.find($slide + '.active').index();

                slides().eq($i).removeClass('active');
                slides().eq($i).fadeOut($transition_time);

                if (slides().length == $i + 1) $i = -1; // loop to start

                slides().eq($i + 1).fadeIn($transition_time);
                slides().eq($i + 1).addClass('active');
            }
            , $transition_time +  $time_between_slides
        );

    });


});

function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
//////////////////////////////
// side nav
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
        } else {
            dropdownContent.style.display = "block";
        }
    });
}
