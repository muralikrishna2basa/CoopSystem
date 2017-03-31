<?php
function errorMessages($messages)
{
    if(count($messages) > 0)
    {
        echo "<div>\n";
        echo "  <dl class=\"toggle-menu has-error\">\n";
        echo "      <dt><h3>エラーが発生しました。</h3></dt>\n";
        echo "      <dd>\n";
        foreach ($messages as $error) {
            echo "          <p>{$error}</p>\n";
        }
        echo "      </dd>\n";
        echo "  </dl>\n";
        echo "</div>";
    }
}
?>