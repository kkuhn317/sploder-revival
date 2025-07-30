<?php

require_once(__DIR__ . '/../content/pages.php');

class GraphicListRenderService
{
    private readonly IGraphicsRepository $graphicsRepository;

    public function __construct(IGraphicsRepository $graphicsRepository)
    {
        $this->graphicsRepository = $graphicsRepository;
    }

    private function renderPartialViewForGraphics(
        array $graphics,
        string $noGraphicsFoundMessage
    ): void {
        if (count($graphics) <= 0) {
            echo "<p>$noGraphicsFoundMessage</p>";
            return;
        }

        ?>
        <div id="viewpage">
            <div class="set">
                <?php 
                $counter = 0;
                foreach ($graphics as $graphic) :
                    if ($graphic['id'] == null) {
                        break;
                    }
                    $counter++;
                ?>
                    <div class="game vignette">
                        <div class="photo">
                            <a href="/members/index.php?u=<?= htmlspecialchars($graphic['username']) ?>">
                                <img src="/graphics/gif/<?= htmlspecialchars($graphic['id']) ?>.gif" width="80" height="80" />
                            </a>
                        </div>
                        <div class="spacer">&nbsp;</div>
                    </div>
                    <?php if ($counter % 4 == 0) : ?>
                        <div class="spacer">&nbsp;</div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="spacer">&nbsp;</div>
            </div>
        </div>
        <?php
    }

    public function renderPartialViewForPublicGraphics(int $offset, int $perPage): void
    {
        $graphics = $this->graphicsRepository->getPublicGraphics($offset, $perPage);
        $total = $this->graphicsRepository->getTotalPublicGraphics();
        
        $this->renderPartialViewForGraphics(
            $graphics,
            "No public graphics have been made yet."
        );
        
        addPagination($total, $perPage, $offset);
    }

    public function renderGraphicsList(array $graphics, int $total): string
    {
        if ($total == 0) {
            return '<div id="viewpage"><div class="set">No public graphics have been made yet.<div class="spacer">&nbsp;</div></div></div>';
        }

        $html = '<div id="viewpage">';
        $html .= '<div class="set">';
        
        $counter = 0;
        foreach ($graphics as $graphic) {
            if ($graphic['id'] == null) {
                break;
            }
            $counter++;
            
            $html .= '<div class="game vignette">';
            $html .= '<div class="photo">';
            $html .= '<a href="/members/index.php?u=' . htmlspecialchars($graphic['username']) . '">';
            $html .= '<img src="/graphics/gif/' . htmlspecialchars($graphic['id']) . '.gif" width="80" height="80" />';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '<div class="spacer">&nbsp;</div>';
            $html .= '</div>';
            
            if ($counter % 4 == 0) {
                $html .= '<div class="spacer">&nbsp;</div>';
            }
        }
        
        $html .= '<div class="spacer">&nbsp;</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}