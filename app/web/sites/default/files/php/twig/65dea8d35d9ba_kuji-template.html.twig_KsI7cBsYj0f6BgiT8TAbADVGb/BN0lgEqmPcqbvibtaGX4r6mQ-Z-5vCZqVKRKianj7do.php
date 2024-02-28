<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* modules/custom/helloworld/templates/kuji-template.html.twig */
class __TwigTemplate_b2e9f3eb46c1c77c37ecdd9844f555f4 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        $this->loadTemplate("@helloworld/tab-template.html.twig", "modules/custom/helloworld/templates/kuji-template.html.twig", 1)->display($context);
        // line 2
        echo "
<div id=\"app\">
    <button id=\"resetGame\">ゲームをリセット</button>
    <button id=\"drawTickets\">チケットを引く</button>
    <input type=\"number\" id=\"selectedCount\" min=\"1\" max=\"10\" placeholder=\"0\">
    <div id=\"results\"></div>
    <div id=\"remaining\">残りチケット数: <span id=\"remainingTickets\">80</span></div>
</div>

<!-- JavaScriptファイルの読み込み -->
";
        // line 12
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("helloworld/helloworld.kuji"), "html", null, true);
    }

    public function getTemplateName()
    {
        return "modules/custom/helloworld/templates/kuji-template.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 12,  41 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/helloworld/templates/kuji-template.html.twig", "/var/www/html/web/modules/custom/helloworld/templates/kuji-template.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("include" => 1);
        static $filters = array("escape" => 12);
        static $functions = array("attach_library" => 12);

        try {
            $this->sandbox->checkSecurity(
                ['include'],
                ['escape'],
                ['attach_library']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
