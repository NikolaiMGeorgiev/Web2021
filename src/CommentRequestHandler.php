<?php
    date_default_timezone_set('Europe/Sofia');
    require_once("AppBootStrap.php");

    AppBootStrap::init();

    class CommentRequestHandler {

        public static function getComments($roomId) {
            if (!$roomId) {
                throw new BadRequestException("Room id should be provided");
            }

            $connection = self::initConnection();
            
            $stmt = $connection->prepare("SELECT * FROM comments WHERE roomId=:roomId ORDER BY createdAt ASC");

            $stmt->execute([
                "roomId" => $roomId
            ]);

            $comments = [];

            while ($row = $stmt->fetch()) {
                $userStmt = $connection->prepare("SELECT * FROM users WHERE id=:userId");
                $userStmt->execute([
                    "userId" => $row["userId"]
                ]);

                $userRow = $userStmt->fetch();

                $user = new User($userRow["name"], $userRow["email"], $userRow["id"]);
                $comment = new Comment($row["content"], $row["createdAt"]);

                $comments[] = [
                    "comment" => $comment,
                    "user" => $userRow["name"]
                ];
            }

            return $comments;
        }

        public static function createComment($commentData, $userId) {
            if (!$commentData) {
                throw new BadRequestException("Comment data should be provided");
            }

            $connection = self::initConnection();

            $stmt=$connection->prepare("INSERT INTO comments (userId, roomId, content) VALUES (:userId, :roomId,
                :content)");
            $stmt->execute([
                "userId" => $userId,
                "roomId" => $commentData["roomId"],
                "content" => $commentData["content"],
            ]);

            $commentId = $connection->lastInsertId();

            return $commentId;
        }

        private static function initConnection() {
            return (new DB())->getConnection();
        }
    }
?>