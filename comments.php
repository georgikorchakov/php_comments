<?php
    class Comments
    {
        public function __construct($database_password, $page_id)
        {
            $this->db = new mysqli(
                '127.0.0.1',
                'root',
                $database_password,
                'comments'
            );

            $this->page_id = $page_id;
            $this->error = '';
        }

        private function disable_attack($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        public function add_comment($name, $content)
        {
            if ($name != '' && $content != '')
            {
                $name = $this->disable_attack($name);
                $content = $this->disable_attack($content);
                
                $query = $this->db->prepare('INSERT INTO comments (name, content, page_id) VALUES (?, ?, ?)');
                $query->bind_param('ssi', $name, $content, $this->page_id);  // s = string
                $query->execute();
            }
            else
            {
                $this->error = "Не сте попълнили някое от полетата!";
            }
        }

        public function get_comments()
        {
            $sql = 'SELECT * FROM comments WHERE page_id = ' . $this->page_id . ' ORDER BY id DESC';
            $result = $this->db->query($sql);
            $comments = $result->fetch_all(MYSQLI_ASSOC);
            return $comments;
        }
    }

    $database_password = $_ENV['DATABASE_PASSWORD'];
    $comments = new Comments($database_password, 1);

    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        $response = $comments->get_comments();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $name = $_POST['name'];
        $content = $_POST['content'];
        $comments->add_comment($name, $content);
        $response = $comments->get_comments();
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Test PHP</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <div class="row" style='padding-top: 30px;'>
            <div class='col-2'></div>
            <div class='col-8'>
                <div class="card bg-light mb-3">
                    <div class="card-header">Коментари</div>
                    <div class="card-body">
                        <span class="text-danger"><?php echo $comments->error; ?></span>
                        <form action="/" method="post">
                            <div class='form-group'>
                                <input class='form-control' type='text' name='name' placeholder='Име'>
                            </div>
                            <div class='form-group'>
                                <label for='commentContent'>Напишете коментар</label>
                                <textarea class='form-control' rows='3' id='commentContent' name='content'></textarea>
                            </div>
                            <input type='submit' class='btn btn-outline-primary' value='Създайте коментар'>
                        </form><hr>

                        <?php foreach ($response as $comment) {?>
                            <div>
                                <h5><?php echo $comment['name']?></h5>
                                <p><?php echo $comment['content']?></p>
                            </div>
                        <?php }?>
                    </div>
                </div>
                
            </div>
            <div class='col-2'></div>
        </div>
    </body>
</html>