<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once ("./includes/header.php"); ?>
    <?php

    ?>
    <div class="w-full h-screen z-[10] fixed" id="OcrOverlay" style="display: none;">
        <div
            class="w-[350px] lg:w-[500px] bg-white z-[20] shadow-lg rounded-lg top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 absolute">
            <div>
                <div class="p-4 absolute z-[20] top-[0] right-[0]">
                    <button class="float-right" onclick="hideOcrOverlay()"><i
                            class="bi bi-x text-xl text-zinc-600"></i></button>
                </div>
                <?php
    if (isset($_SESSION['username'])) {
        ?>
<form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
<div class="p-4 w-full flex mt-[45px] w-full flex-col justify-center items-center gap-8 border-dashed border-[1px]">
    <div id="preview" class="text-sm font-semibold text-gray-400 max-w-full h-auto"></div>
    <input type="file" id="fileInput" name="fileToUpload" class="block bg-zinc-100 p-2">
    <button type="submit" id="uploadButton" name="submit" class="transition disabled:bg-zinc-200 hover:bg-black bg-orange-500 w-full py-2 text-white font-semibold" disabled>Upload</button>
</div>

<style>
    /* Add this CSS */
    #preview img {
        max-width: 100%; /* Ensure the image doesn't exceed its container */
        height: auto; /* Maintain aspect ratio */
    }
</style>

<script>
    document.getElementById('fileInput').addEventListener('change', function (event) {
        const preview = document.getElementById('preview');
        const uploadButton = document.getElementById('uploadButton');
        preview.innerHTML = ''; // Clear previous content

        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
            uploadButton.disabled = false; // Enable the upload button
        } else {
            preview.textContent = 'No file selected';
            uploadButton.disabled = true; // Disable the upload button
        }
    });
</script>

                </form>
        <?php
    } else {
        //Non Logged In Here
            ?>
            <div
                        class="p-4 w-full flex mt-[45px] w-full flex-col justify-center items-center gap-4">
                        <p class="text-zinc-400 font-semibold text-md">You Must Login Before Continue!</p>
                        <img src="./core/img/login-alt.gif" class="w-[200px]">
                        <a onclick="hideOcrOverlay()" id="uploadButton"
                            class="transition text-center disabled:bg-zinc-200 hover:bg-black bg-orange-500 w-full py-2 text-white font-semibold""
                            disabled>Reload</a>
                    </div>
            <?php
    }
                ?>
                                    </div>
            </div>
        </div>
    </div>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoubtHunt - Answer For Every Doubt</title>
</head>

<body>
    <div>
        <div class="max-w-[1280px] m-auto">
            <br>
            <div
                class="flex-wrap p-[40px] flex-col flex items-center h-auto pt-40 pb-40 lg:h-screen lg:flex-row gap-20 mt-20 lg:mt-0">
                <div class="flex-[1.5]">
                    <h1 class="text-3xl leading-[40px] lg:text-5xl lg:leading-[60px] font-bold text-gray-800">Clear Your
                        <span class="text-orange-400"> Doubts </span> with Experts
                    </h1>
                    <div
                        class="rounded-sm shadow-lg max-w-[1050px] p-2 bg-white rounded-sm border-gray-200 rounded-xl mt-8">
                        <div class="flex flex-row items-center relative">
                            <div class="flex-[10] bg-white ">
                                <i class="bi bi-keyboard absolute text-gray-400 left-[10px] absolute top-[0px]"
                                    style="font-size: 26px;"></i>
                                <input type="text"
                                    class="w-full pl-12 py-2 outline-none border-[1px] border-gray-200 rounded-l-lg"
                                    name="query" id="searchInput" placeholder="Search Question..." />
                                <div id="searchResults" class="absolute w-full lg:max-w-[600px] bg-white rounded-b-xl shadow-xl hidden">
                                </div>
                            </div>
                            <div class="flex-1">
                                <button type="button" id="RunOcr"
                                    class="py-2 px-4 bg-orange-500 rounded-r-lg text-white font-semibold border-[1px] border-orange-500">
                                    <i class="bi bi-camera"></i></button>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="flex flex-wrap flex-row gap-2 items-center">
                        <i class="bi bi-chat-dots-fill text-2xl text-orange-600"></i>
                        <p class="text-xl font-semibold text-zinc-800">More Than 30k Doubts Solved</p>
                    </div>
                    <br />
                    <div class="flex flex-wrap flex-row gap-2 items-center">
                        <i class="bi bi-stars text-2xl text-orange-600"></i>
                        <p class="text-xl font-semibold text-zinc-800">Solutions by verified experts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const OcrButton = document.getElementById('RunOcr');
        const OcrOverlay = document.getElementById('OcrOverlay');
        // Add click event listener to the loginOTP button
        OcrButton.addEventListener('click', function () {
            console.log("helloWorld");
            // Toggle the display of the overlay
            OcrOverlay.style.display = (OcrOverlay.style.display === 'block') ? 'none' : 'block';
        });

        function hideOcrOverlay() {
            OcrOverlay.style.display = 'none';
        }

        function liveSearch() {
            var query = document.getElementById('searchInput').value.trim(); // Get the query from the input field
            var searchResults = document.getElementById('searchResults'); // Get the container for search results

            // Hide the search results container if the query is empty
            if (query === "") {
                searchResults.style.display = "none";
                return; // Exit the function early if the query is empty
            }

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // Update the search results container with the response
                    searchResults.innerHTML = this.responseText;
                    // Show or hide the search results container based on the response
                    if (this.responseText.trim() !== "") {
                        searchResults.style.display = "block";
                    } else {
                        searchResults.style.display = "none";
                    }
                }
            };
            xhttp.open("GET", "routes/searchRes.php?q=" + query, true); // Send GET request with the query
            xhttp.send();
            console.log(query);
        }

        // Attach event listener to input field to trigger live search
        document.getElementById('searchInput').addEventListener('input', liveSearch);
    </script>
    <?php include_once ("./includes/footer.php"); ?>
</body>
</html>