<?php

class FriendsListRenderService
{
    private readonly IFriendsRepository $friendsRepository;

    public function __construct(IFriendsRepository $friendsRepository)
    {
        $this->friendsRepository = $friendsRepository;
    }

    private function getAvatarPath(string $username): string
    {
        if (file_exists('../avatar/a/' . $username . '.png')) {
            return $username;
        }
        return 'fb/noob';
    }

    private function renderFriend(array $friend, string $currentUser, bool $isBested, bool $showActions): string
    {
        $friendUsername = ($friend['user1'] == $currentUser) ? $friend['user2'] : $friend['user1'];
        $avt = $this->getAvatarPath($friendUsername);
        $bestClass = $isBested ? ' friend_48_best' : '';

        $html = "";

        $html .= '<div style="margin-left:7px;height:90px" class="friend friend_48' . $bestClass . '">';
        $html .= '<a class="name" href="../members/index.php?u=' . htmlspecialchars($friendUsername) . '">';
        $html .= '<img src="../avatar/a/' . htmlspecialchars($avt) . '.png" width="48" height="48" /></a>';
        $html .= '<a class="name" href="../members/index.php?u=' . htmlspecialchars($friendUsername) . '">' . htmlspecialchars($friendUsername) . '</a>';
        
        if ($showActions) {
            if ($isBested) {
                $html .= '<span><a style="color:#666" href="php/unbest.php?u=' . htmlspecialchars($friendUsername) . '">Unbest</a></span>';
            } else {
                $html .= '<span><a style="color:#666" href="php/best.php?u=' . htmlspecialchars($friendUsername) . '">Best</a></span>';
            }
            $html .= '<span><a style="color:#666" href="php/unfriend.php?u=' . htmlspecialchars($friendUsername) . '">Unfriend</a></span>';
        }
        $html .= '</div>';
        return $html;
    }

    public function renderPartialViewForRecentFriends(string $username, bool $showActions = false): string
    {
        $db = getDatabase(); // TODO: Move to repository when available
        
        $html = "";

        $bestedFriends = $db->query("SELECT user1,user2
            FROM friends
            WHERE (bested=true)
            AND (user1=:sender_id)
            ORDER BY id DESC
            LIMIT 30", [
                ':sender_id' => $username
        ]);
        
        $newLimit = 30 - count($bestedFriends);
        
        $acceptedFriends = $db->query("SELECT user1, user2
            FROM friends
            WHERE (bested = false)
            AND (user1=:sender_id)
            ORDER BY id DESC LIMIT $newLimit", [
                ':sender_id' => $username
        ]);

        $totalFriends = count($acceptedFriends) + count($bestedFriends);
        
        if ($totalFriends == 0) {
            return "";
        }

        if($showActions) {
            $html .= '<h4>Recent Friends</h4>';
        }

        $html .= '<div id="friends">';

        // Render bested friends
        foreach ($bestedFriends as $friend) {
            $html .= $this->renderFriend($friend, $username, true, $showActions);
        }

        // Render regular friends
        foreach ($acceptedFriends as $friend) {
            $html .= $this->renderFriend($friend, $username, false, $showActions);
        }

        $html .= "<div class='spacer'></div></div>";
        
        return $html;
    }
}
