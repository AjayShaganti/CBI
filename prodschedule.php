<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBI | Production Schedule</title>
    <!-- References to external basic CSS file -->
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <!-- Favicon for tab -->
    <link rel="icon" type="image/x-icon" href="images/game-fill.png">
    <!-- Reference to web icons from Remixicon.com -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Reference to web icons from Fontawesome -->
    <script src="https://kit.fontawesome.com/d7376949ab.js" crossorigin="anonymous"></script>
    <!-- References to external fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .{
            display: flex;
        }

        /* Your existing styles here */

        /* Colorful styles for the months tabs */
        .tab {
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 4px 4px 0 0;
            display: inline-block;
            margin: 5px;
            width: auto;
            text-align: center;
            font-weight: bold;
            color: #fff;
            background-color: #FF5E60; /* Red */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease-in-out, transform 0.2s ease-in-out;
        }

        .tab:hover {
            background-color: #ff5252; /* Lighter Red */
            transform: translateY(-4px);
            transition: all 0.1s ease 0s;
        }

        .active-tab {
            background-color: #e57373; /* Light Red */
        }

        /* Style for the months container */
        .months-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: auto;
            animation: fadeIn 1s ease-in-out;
            overflow: auto; /* Enable horizontal scrolling if needed */
            white-space: nowrap; /* Prevent the months from wrapping to multiple rows */
            margin-left: 0px; /* Add left margin to show starting months */
        }

        /* Styles for navigation buttons container */
        .navigation-buttons-container {
            display: flex;
            align-items: center;
            //justify-content: space-between;
            margin-bottom: 10px;
        }

        /* Styles for navigation buttons */
        .navigation-button {
            cursor: pointer;
            font-size: 24px;
            padding: 6px 12px;
            border-radius: 50%;
            //background-color: #f1f1f1;
            color: #333;
            //box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
        }

        .navigation-button:hover {
            box-shadow: 10px 4px 8px rgba(10, 0, 1, 0.1);
        }

        /* Additional styles for default selected month */
        .default-selected {
            //background-color: #e57373; /* Light Red */
        }

        #year-container {
            display: flex;
            align-items: center;
            margin-left: 1%;
            margin-top: -2%;
        }

        #year {
            padding: 5px;
            border-radius: 4px;
            font-size: 16px;
            max-width: 10%;
            width: auto;
        }

        label {
            font-weight: bold;
        }

        #week {
            padding: 5px;
            border-radius: 4px;
            font-size: 16px;
            max-width: 20%;
            width: auto;
        }

        #week1 {
            margin-left: 1%;
            margin-top: -3%;
            z-index: 1;
        }

        #left {
            margin-left: 21.5%;
        }

        #cbi-logo img {
            width: 10.2%;
            margin-left: 88%;
            margin-top: -7.5%;
        }

        hr {
            margin-top: -2%;
            border: none;
            /* top    */ border-top: 1px solid #ccc;
            /* middle */ background-color: #ddd;
            color: #ddd;
            /* bottom */ border-bottom: 1px solid #eee;
            height: 1px;
            *height: 3px; /* IE6+7 need the total height */
        }

        /* New styles for the navigation menu */
        #menu1 {
            //display: flex;
            flex-direction: column; /* Display the menu items vertically */
            background-color: transparent;
            position: fixed; /* Keep the menu fixed on the screen */
            top: 0;
            left: 0px; /* Initially hide the menu off the left side of the screen */
            width: 250px;
            height: 100%;
            //padding: 10px;
			margin-top:8%;
            transition: left 0.3s ease; /* Add smooth transition animation */
        }


        /* Style for the menu items */
        ul {
            list-style: none;
            margin: 0;
            padding: 0;
			background:#fff !important;
        }        
		ul:hover {
			background:#transparent !important;
        }
		

        li {
           
		    padding:1%;
        }
		  li:hover {
		   background:#fff !important;
        }

        a {
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
        }
		#burger1 {
			display:flex;
			margin-left:-48%;
		}

        /* Media query to show the menu when the checkbox is checked (hamburger icon clicked) */
        @media (max-width:3000px) {
            nav {
                left: 0; /* Display the menu when checkbox is checked */
            }


        }

    </style>
</head>
<body>
    <nav id="menu1">

        <ul>
            <li><a href="Dashboard.php"><p>Dashboard</p></a></li>
            <li><a href="Stock.php"><p>Stock</p></a></li>
            <li><a href="prodschedule.php"><p>Production Schedule</p></a></li>
            <li><a href="updatebypartno1.php"><p>Update by PartNumber</p></a></li>
            <li><a href="viewjobs1.php"><p>View Jobs</p></a></li>
            <li><a href="managejobs1.php"><p>Manage Jobs</p></a></li>
            <li><a href="Viewusers1.php"><p>View Users</p></a></li>
            <li><a href="manageusers1.php"><p>Manage Users</p></a></li>
            <li><a href="updatestock1.php"><p>Update Stock Data</p></a></li>
            <li><a href="usage.php"><p>Usage</p></a></li>
            <li><a href="reorder.php"><p>Re-Order</p></a></li>
            <li><a href="orderhistory.php"><p>Order History</p></a></li>
        </ul>
    </nav>

    <div id="top-head">
        <center>
            <h1> Production Schedule </h1>
        </center>
        <div id="year-container">
            <label for="year">Select Year: </label>
            <select id="year" onchange="updateMonthsAndWeeks()">
                <!-- The years will be populated dynamically -->
            </select>
        </div>

        <div class="navigation-buttons-container">
            <div id="left" class="navigation-button" onclick="navigateMonths(-1)">
                &#9664; <!-- Left arrow icon -->
            </div>
            <div class="months-container" id="months-container">
                <!-- The months will be populated dynamically as tabs -->
            </div>
            <div class="navigation-button" onclick="navigateMonths(1)">
                &#9654; <!-- Right arrow icon -->
            </div>
        </div>
        <div id="week1">
            <label for="week">Select Week:</label>
            <select id="week">
                <option selected disabled>No weeks available for this month</option>
            </select>
			</diV>
			<div>
	    <input type="checkbox" id="burger1">
        <label for="burger1" class="burgerbtn">
            <i class="ri-menu-line"></i>
        </label>
        <div id="cbi-logo"><img src="images/cbi-logo.png" alt="CBI logo"></div>	
        <hr>
    </div>
  <script>
    let currentMonthIndex = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    const months = [
      "January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];

    function populateYears() {
      const yearDropdown = document.getElementById("year");
      const yearsToShow = 15; // Total years to display, 10 previous years and 5 forward years

      for (let i = currentYear - 10; i <= currentYear + 4; i++) {
        const option = document.createElement("option");
        option.value = i;
        option.text = i;
        yearDropdown.appendChild(option);
      }

      yearDropdown.value = currentYear; // Set current year as default selected
    }

    function getWeeksOfMonth(year, month) {
      const weeks = [];
      const firstDay = new Date(year, month, 1);
      const nextFirstDay = new Date(year, month+1, 1);
      const nextFirstDay1 = new Date(year, month+1, 1);

      // Adjust the first day to the first Monday of the month
      while (firstDay.getDay() !== 1) {
        firstDay.setDate(firstDay.getDate() + 1);
      }      
	  while (nextFirstDay.getDay() !== 1) {
        nextFirstDay.setDate(nextFirstDay.getDate() + 1);
      }
		 //console.log(nextFirstDay);
		 
      let currentWeek = [new Date(firstDay)]; // Start a new week with the first Monday
	   var currentDate;

      for (let day = firstDay.getDate() + 1; day <= new Date(year, month + 1,0).getDate(); day++) {
        currentDate = new Date(year, month, day);
		 
        if (currentDate.getDay() === 1) {
          weeks.push(currentWeek);
          currentWeek = [currentDate]; // Start a new week
        } else {
          currentWeek.push(currentDate);	
        }
      }
	  
	  for ( let day2=nextFirstDay1.getDate();day2<nextFirstDay.getDate();day2++)
	  {
		        currentDate2 = new Date(year, month+1, day2);
		 
        if (currentDate2.getDay() === 1) {
          weeks.push(currentWeek);
          currentWeek = [currentDate2]; // Start a new week
        } else {
          currentWeek.push(currentDate2);	
        }
	  }
	  
		
      if (currentWeek.length > 0) {
        weeks.push(currentWeek);
      }

      return weeks;
    }

    function updateMonthsAndWeeks() {
      const monthsContainer = document.getElementById("months-container");
      monthsContainer.innerHTML = ""; // Clear existing tabs

      for (let i = 0; i < months.length; i++) {
        const tab = document.createElement("div");
        tab.className = "tab";
        tab.textContent = months[i];
        tab.onclick = () => updateWeeks(months[i]);
        monthsContainer.appendChild(tab);
      }

      currentMonthIndex = new Date().getMonth();
      highlightActiveTab(months[currentMonthIndex]);
      updateWeeks(months[currentMonthIndex]);
    }

    function updateWeeks(selectedMonth) {
      const yearDropdown = document.getElementById("year");
      const selectedYear = parseInt(yearDropdown.value, 10);
      const monthIndex = months.indexOf(selectedMonth);

      const weekDropdown = document.getElementById("week");
      weekDropdown.innerHTML = "";

      const weeksOfMonth = getWeeksOfMonth(selectedYear, monthIndex);

      for (let i = 0; i < weeksOfMonth.length; i++) {
        const weekDates = weeksOfMonth[i];
        const startWeekDate = formatDate(weekDates[0]);
        const endWeekDate = formatDate(weekDates[weekDates.length - 1]);

        const option = document.createElement("option");
        option.value = i;
        option.text = `Week ${i + 1} (${startWeekDate} to ${endWeekDate})`;
        weekDropdown.appendChild(option);
      }
    }

    function formatDate(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, "0");
      const day = String(date.getDate()).padStart(2, "0");
      return `${day}-${month}-${year}`;
    }

    function navigateMonths(step) {
      currentMonthIndex += step;

      if (currentMonthIndex < 0) {
        currentMonthIndex = 11;
        currentYear--;
        document.getElementById("year").value = currentYear;
      } else if (currentMonthIndex > 11) {
        currentMonthIndex = 0;
        currentYear++;
        document.getElementById("year").value = currentYear;
      }

      const selectedMonth = months[currentMonthIndex];
      updateWeeks(selectedMonth);
      highlightActiveTab(selectedMonth);
    }

    function highlightActiveTab(selectedMonth) {
      const tabs = document.querySelectorAll(".tab");
      tabs.forEach((tab) => {
        tab.classList.remove("active-tab", "default-selected");
        if (tab.textContent === selectedMonth) {
          tab.classList.add("active-tab");
        }
        if (tab.textContent === months[new Date().getMonth()] && currentYear === new Date().getFullYear()) {
          tab.classList.add("default-selected");
        }
      });
    }
    // Initial population of the year dropdown and months/weeks tabs
    populateYears();
    updateMonthsAndWeeks();
	
const burger = document.getElementById("burger1");
const navMenu = document.getElementById("menu1");

burger.addEventListener("change", () => {
    if (burger.checked) {
        navMenu.style.left = "0"; // Display the menu when checkbox is checked
    } else {
        navMenu.style.left = "-250px"; // Hide the menu when checkbox is not checked
    }
});

  </script>
</body>
</html>
