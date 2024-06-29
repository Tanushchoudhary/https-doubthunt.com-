<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once ("./includes/header.php"); ?>
    <title>Contact Us - DoubtHunt</title>
</head>

<body>
    <br>
    <div class="mt-[120px] mb-[80px] p-4">
        <div class="max-w-[1000px] m-auto">
            <div class="flex flex-wrap gap-4 flex-col lg:flex-row">
                <div class="flex-[1] bg-white p-10 border-[1px] shadow-xl">
                    <h1 class="text-xl font-semibold">Contact Us</h1>
                    <br />
                    <p class="font-bold">INDIA :</p><br />
                    <p><i class="bi bi-geo-alt mr-2"></i> Renwal Road , Jobner
                        P.O. - Jobner , District Jaipur, Rajasthan,PIN Code - 303328
                    </p><br />
                    <p><i class="bi bi-telephone"></i> +91 89493 92577</p>
                    <div class="mt-4" style="max-width:100%;overflow:hidden;color:red;width:100%;height:300px;">
                        <div id="google-maps-canvas" style="height:100%; width:100%;max-width:100%;"><iframe
                                style="height:100%;width:100%;border:0;" frameborder="0"
                                src="https://www.google.com/maps/embed/v1/place?q=Renwal+Road+,+Jobner++P.O.+-+Jobner+,+District+Jaipur,+Rajasthan,PIN+Code+-+303328&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"></iframe>
                        </div><a class="our-googlemap-code" href="https://www.bootstrapskins.com/themes"
                            id="enable-map-data">premium bootstrap themes</a>
                        <style>
                            #google-maps-canvas .text-marker {}

                            .map-generator {
                                max-width: 100%;
                                max-height: 100%;
                                background: none;
                        </style>
                    </div>
                </div>
                <div class="flex-[1.2] p-10">
                    <center>
                        <p class="font-bold text-2xl">Let's Get In Touch</p>
                        <p class="mt-2 mb-4 text-zinc-500">You can contact us any way that is convenient for you.</p>
                    </center>
                    <form action="contact.php" method="POST">
                        <input type="text" name="fullName" placeholder="Full Name"
                            class="w-full p-2 border-[1px] mt-4" />
                        <input type="text" name="email" placeholder="Email or Mobile"
                            class="w-full p-2 border-[1px] mt-4" />
                        <textarea name="message" placeholder="What's Your Query?"
                            class="w-full resize-none h-[180px] border-[1px] p-4 mt-4"></textarea>
                        <button type="submit"
                            class="mt-4 bg-orange-500 text-white w-full py-2 font-semibold">Send <i class="bi bi-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include_once ("./includes/footer.php"); ?>
</body>

</html>