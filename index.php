<?php
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="main">
        <div class="form-section">
            <form action="app/add.php" method="POST" autocomplete="off">
                <?php if (isset($_GET['mess']) && $_GET['mess'] == 'error') { ?>
                    <input type="text" name="title" placeholder="This field is required" />
                    <button type="submit">Add</button>

                <?php } else { ?>
                    <input type="text" name="title" placeholder="Enter todo here..." />
                    <button type="submit">Add</button>
                <?php } ?>
            </form>
        </div>
        <?php
        $todolist = $conn->query("SELECT * FROM todolist ORDER BY id DESC");
        ?>
        <div class="todolist-section">
            <?php if ($todolist->rowCount() <= 0) { ?>
                <div class="todo-item">
                    There are no todos! You can add one by typing into input.
                </div>
            <?php } ?>

            <?php while ($todo = $todolist->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <?php if ($todo['checked']) { ?>
                        <div class="title-box">
                            <input type="checkbox" class="check-box" data-todo-id="<?php echo $todo['id']; ?>" checked />
                            <h2 class="checked">
                                <?php echo $todo['title'] ?>
                            </h2>
                        </div>
                    <?php } else { ?>
                        <div class="title-box">
                            <input type="checkbox" data-todo-id="<?php echo $todo['id']; ?>" class="check-box" />
                            <h2>
                                <?php echo $todo['title'] ?>
                            </h2>
                        </div>
                    <?php } ?>
                    <small>created at: <?php echo $todo['date_time'] ?></small>
                    <button id="<?php echo $todo['id']; ?>" class="remove-to-do">Remove</button>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="js/jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.remove-to-do').click(function () {
                const id = $(this).attr('id');

                $.post("app/remove.php",
                    {
                        id: id
                    },
                    (data) => {
                        if (data) {
                            $(this).parent().hide(600);
                        }
                    }
                );
            });

            $(".check-box").click(function (e) {
                const id = $(this).attr('data-todo-id');

                $.post('app/check.php',
                    {
                        id: id
                    },
                    (data) => {
                        if (data != 'error') {
                            const h2 = $(this).next();
                            if (data === '1') {
                                h2.removeClass('checked');
                            } else {
                                h2.addClass('checked');
                            }
                        }
                    }
                );
            });
        });
    </script>
</body>
</html>