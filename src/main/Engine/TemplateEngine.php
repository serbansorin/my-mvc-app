<?php

namespace Main\Engine;

class TemplateEngine
{
    private $templateDir;

    public function __construct($templateDir)
    {
        $this->templateDir = rtrim($templateDir, '/');
    }

    public function render($template, $data = [])
    {
        $templatePath = $this->templateDir . '/' . $template . '.php';
        if (!file_exists($templatePath)) {
            throw new \Exception("Template file not found: $templatePath");
        }

        $content = file_get_contents($templatePath);
        $content = $this->compileBlade($content);

        ob_start();
        extract($data);
        eval('?>' . $content);
return ob_get_clean();
}

private function compileBlade($content)
{
$content = preg_replace('/{{\s*(.+?)\s*}}/', '<?php echo htmlspecialchars($1); ?>', $content);
$content = preg_replace('/@if\s*\((.*?)\)/', '<?php if ($1): ?>', $content);
$content = preg_replace('/@else/', '<?php else: ?>', $content);
$content = preg_replace('/@elseif\s*\((.*?)\)/', '<?php elseif ($1): ?>', $content);
$content = preg_replace('/@endif/', '<?php endif; ?>', $content);
$content = preg_replace('/@foreach\s*\((.*?)\)/', '<?php foreach ($1): ?>', $content);
$content = preg_replace('/@endforeach/', '<?php endforeach; ?>', $content);

return $content;
}
}