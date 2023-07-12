// Defines add course form and button
const popCourseForm = document.getElementById("popCourseForm");
var button = document.getElementById("addCourseButton");

// Function to button
button.addEventListener("click", function(){
    document.getElementById("popCourseForm").style.display = "block";
});