<!-- db connection -->
<?php 
    require_once "login.php";
?>

<!-- sever-side validation -->
<?php 
    $nameErr = $emailErr = $commentErr = "";
    $name = $email = $comment = "";
    $success = false;


    if (isset($_POST['submitBtn'])) {

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $comment = trim($_POST['comment']);
        
        if(empty($name)) {
            $nameErr = "Name is required";
        }

        if(empty($email)){
            $emailErr = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }

        if(empty($comment)){
            $commentErr = "A comment is required and must be more than 20 characters";
        } elseif (strlen($comment) < 20) {
            $commentErr = "The comment must be at least 20 characters";
        }

        if(empty($nameErr) && empty($emailErr) && empty($commentErr)) {
            
            try {
                $stmt = $pdo->prepare("INSERT INTO feedback(name, email, comment) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $comment]);

                $success = true;

                $name = $email = $comment = "";
                
            } catch (PDOException $e) {
                $success = false;
                $nameErr = "Error while saving feedback, Please try again";
            }

        }
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
</head>
<body>
    <form method="POST" action="feedback.php">
        <hr><header><h1>FeedBack Form</h1></header><hr>

        <table>
            <tr>
                <td>Name:</td>
                <td>
                    <input id="name" type="text" name="name" value="<?php echo $name; ?>">
                    <?php if (!empty($nameErr)): ?>
                        <br><span style="color: red;"><?php echo $nameErr; ?></span>
                    <?php endif; ?>
                </td>
                
            </tr>
            <tr>
                <td>Email:</td>
                <td><input id="email" type="email" name="email" value="<?php echo $email; ?>">
                    <?php if (!empty($emailErr)): ?>
                        <br><span style="color: red;"><?php echo $emailErr; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Comment:</td>
                <td><textarea id="comment" name="comment" rows="5" cols="30" style="resize: none;"><?php echo $comment; ?></textarea>
                    <?php if (!empty($commentErr)): ?>
                        <br><span style="color: red;"><?php echo $commentErr; ?></span>
                    <?php endif; ?>    
                </td>
            </tr>
            <tr>
                <td><button id="submitBtn" type="submit" name="submitBtn">Submit</button></td>
            </tr>
        </table>
        <?php 
            if(isset($_POST['submitBtn'])) {
                if($success) {
                    echo "<p>Thank You for your feedback!</p>";
                }
            }
        ?>
    </form>

    <!-- Client side validation -->
    <script>
        document.querySelector("form").addEventListener("submit", validation);

        function validation(e){
            const name = document.getElementById("name").value.trim();
            const email = document.getElementById("email").value.trim();
            const comment = document.getElementById("comment").value.trim();

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (name.length === 0) {
                e.preventDefault();
                alert("Please enter a name");
                return false;
            }

            if (!emailPattern.test(email)) {
                e.preventDefault();
                alert("Please enter a valid email");
                return false;
            }

            if (comment.length < 20) {
                e.preventDefault();
                alert("Must be at least 20 characters");
                return false;
            }

            return true;
        }
    </script>

    <!-- Previous feedback table -->
    <hr><h2>Previous Feedback</h2><hr>
    <?php 
        try {
            $stmt = $pdo->query("SELECT * FROM feedback ORDER BY submitted_at DESC");
            $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($feedbacks) > 0) {
                echo "<table border='1' cellpadding='10'>";
                echo "<tr><th>Name</th><th>Email</th><th>Comment</th><th>Date Submitted</th><tr>";
                
                foreach ($feedbacks as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['comment']) . "</td>";
                    echo "<td>" . $row['submitted_at'] . "</td>";
                    echo "</tr>";
                }

            } else {
                echo "No feedback has been submitted yet";
            }

        } catch (PDOException $e) {
            echo "<p>Error loading feedback.</p>";
        }
    
    ?>
    


</body>
</html>
