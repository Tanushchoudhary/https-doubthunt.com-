<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include_once ("./includes/header.php"); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoubtHunt - Answer For Every Doubt</title>
</head>

<body>
    <br />
    <div class="mt-[100px] mb-[50px]">
        <div class="m-auto max-w-[1200px] p-4">
            <div class="flex flex-wrap gap-10 flex-col lg:flex-row">
                <div class="flex-[1]">
                    <div class="bg-white p-4 border-[1px]">
                        <h1 class="font-semibold text-2xl">My Questions</h1>
                        <div class="bg-white">
                            <table class="table-auto mt-4 w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-950">
                                        <th
                                            class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            ID</th>
                                        <th
                                            class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Question</th>
                                        <th
                                            class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody id="questions-table">
                                    <?php
                                    $i = 0;
                                    $sql = "SELECT q.id, q.question, q.media
                                    FROM questions q 
                                    LEFT JOIN answers a ON q.id = a.qID 
                                    WHERE a.qID IS NULL AND q.userID = '$global_id'
                                    ORDER BY RAND() 
                                    LIMIT 5;
                                    ";
                                    $query = mysqli_query($conn, $sql);

                                    while ($row = mysqli_fetch_assoc($query)) {
                                        $id = $row['id'];
                                        $question = $row['question'];
                                        $media = $row['media'];
                                        if ($media == "") {
                                            $text_media = "";
                                        } else {
                                            $text_media = "View Media";
                                        }
                                        $i++;
                                        echo "<tr class='border-b border-gray-200'>
                                                <td class='px-4 py-4 whitespace-nowrap'>$i</td>
                                                <td class='px-4 py-4'>$question <a href='./uploads/$media' target='blank_' class='text-xs rounded-lg text-orange-500 underline'>$text_media</a></td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="flex-[1]">
                    <div class="bg-white p-4 border-[1px]">
                        <h1 class="font-semibold text-2xl">Resolved</h1>
                        <table class="table-auto mt-4 w-full bg-white">
                            <thead>
                                <tr class="bg-gray-950">
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Question</th>
                                    <th
                                        class="px-4 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody id="questions-table">
                                <?php
                                $i = 0;
                                $sql = "SELECT q.id, q.question, q.media
                                    FROM questions q
                                    LEFT JOIN answers a ON q.id = a.qID
                                    WHERE a.qID IS NOT NULL AND q.userID = '$global_id'
                                    ";
                                $query = mysqli_query($conn, $sql);

                                while ($row = mysqli_fetch_assoc($query)) {
                                    $id = $row['id'];
                                    $question = $row['question'];
                                    $i++;
                                    $media2 = $row['media'];
                                    if ($media2 == "") {
                                        $text_media2 = "";
                                    } else {
                                        $text_media2 = "View Media";
                                    }
                                    echo "<tr class='border-b border-gray-200'>
                                                <td class='px-4 py-4 whitespace-nowrap'>$i</td>
                                                <td class='px-4 py-4'>$question <a href='./uploads/$media2' target='blank_' class='text-xs rounded-lg text-orange-500 underline'>$text_media2</a></td>
                                                <td class='px-4 py-4'>
                                                <a href='question.php?id=$id'
                                                    class='bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2'>View</a>
                                            </td>
                                              </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once ("./includes/footer.php"); ?>
</body>

</html>