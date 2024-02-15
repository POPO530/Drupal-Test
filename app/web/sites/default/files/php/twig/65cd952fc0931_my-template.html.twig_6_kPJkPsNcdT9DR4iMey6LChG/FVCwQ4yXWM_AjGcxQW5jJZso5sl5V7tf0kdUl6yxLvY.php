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

/* modules/custom/helloworld/templates/my-template.html.twig */
class __TwigTemplate_937f1961b9db8b1d3cf2e48ad068f9c3 extends \Twig\Template
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
        $this->loadTemplate("@helloworld/tab-template.html.twig", "modules/custom/helloworld/templates/my-template.html.twig", 1)->display($context);
        // line 2
        echo "
<!-- テスト用Twigテンプレート -->
<p>Test twig template!</p>

<input type=\"text\" id=\"searchInput\" onkeyup=\"filterTable()\" placeholder=\"Search for phone number\">

";
        // line 8
        if ( !twig_test_empty(($context["variables"] ?? null))) {
            // line 9
            echo "    <!-- データが存在する場合のテーブル -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            ";
            // line 22
            echo "        </tbody>
    </table>
";
        } else {
            // line 25
            echo "    <!-- データが存在しない場合のメッセージ -->
    <p>No contacts found.</p>
";
        }
        // line 28
        echo "
<!-- JavaScriptファイルの読み込み -->
";
        // line 30
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("helloworld/helloworld.accordion"), "html", null, true);
    }

    public function getTemplateName()
    {
        return "modules/custom/helloworld/templates/my-template.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  79 => 30,  75 => 28,  70 => 25,  65 => 22,  51 => 9,  49 => 8,  41 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/helloworld/templates/my-template.html.twig", "/var/www/html/web/modules/custom/helloworld/templates/my-template.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("include" => 1, "if" => 8);
        static $filters = array("escape" => 30);
        static $functions = array("attach_library" => 30);

        try {
            $this->sandbox->checkSecurity(
                ['include', 'if'],
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
