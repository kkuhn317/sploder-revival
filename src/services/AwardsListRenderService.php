<?php

class AwardsListRenderService 
{
    private readonly IAwardsRepository $awardsRepository;
    
    public function __construct(IAwardsRepository $awardsRepository)
    {
        $this->awardsRepository = $awardsRepository;
    }

    public function getAwardCount(string $username): int
    {
        return $this->awardsRepository->getAwardCount($username);
    }

    public function getAwardsForPage(string $username, int $page, int $perPage = 50): array
    {
        return $this->awardsRepository->getAwardsPage($username, $page, $perPage);
    }

    public function renderAwardsList(array $awards, array $material_list): void
    {
        $html = "";
        foreach ($awards as $award) {
            $award['material_name'] = $material_list[$award['material']];
            $shinestyle = "";
            
            $html .= '<div class="award award_64 special_0">';
            $html .= '<div class="layer shine" ' . $shinestyle . '></div>';
            $html .= '<div class="layer_mini" style="background-image: url(\'medals/px64/' . $award['style'] . $award['material'] . $award['color'] . $award['icon'] . '.gif\');"></div>';
            $html .= '<dl class="plaque">';
            $html .= '<dt>Level ' . $award['level'] . '<br /> ' . $award['material_name'] . $award['category'] . ' Award:</dt>';
            $html .= '<dd>' . $award['message'] . '</dd>';
            $html .= '<dd class="award_cite">from <a href="../members/index.php?u=' . $award['username'] . '">' . $award['username'] . '</a></dd>';
            $html .= '</dl>';
            $html .= '</div>';
        }
        echo $html;
    }

    public function renderAwardsListWithPagination(array $awards, array $material_list, int $offset, int $perPage, int $total): void
    {
        $this->renderAwardsList($awards, $material_list);
        echo '<div class="spacer">&nbsp;</div><br><br>';
        echo '<div style="margin-right:90px;">';
        addPagination($total, $perPage, $offset);
        echo '</div>';
    }
}
