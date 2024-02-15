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

/* modules/custom/helloworld/templates/tab-template.html.twig */
class __TwigTemplate_fb6d187eda6519d8c0da513d91c56fae extends \Twig\Template
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
        echo "<!-- HelloWorldモジュール用のスタイルシートの読み込み -->
<link rel=\"stylesheet\" href=\"/modules/custom/helloworld/css/styles.css\">

<!-- タブナビゲーションのリンク一覧 -->
<ul class=\"tabs-navigation\">
  <li><a href=\"/test/contact/display\">お問い合わせ2管理</a></li>
  <li><a href=\"/test/contact\">お問い合わせ2</a></li>
  <li><a href=\"/dice\">さいころ当てゲーム</a></li>
  <li><a href=\"/hello\">ハローページ</a></li>
  <li><a href=\"/hello/contact\">お問い合わせ</a></li>
  <li><a href=\"/hello/submissions\">お問い合わせ管理</a></li>
</ul>

<!-- HelloWorldモジュール用のJavaScriptファイルの読み込み -->
<script src=\"/modules/custom/helloworld/js/scripts.js\"></script>";
    }

    public function getTemplateName()
    {
        return "modules/custom/helloworld/templates/tab-template.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/helloworld/templates/tab-template.html.twig", "/var/www/html/web/modules/custom/helloworld/templates/tab-template.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array();
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
                [],
                []
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
