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

/* modules/custom/helloworld/templates/contact-template.html.twig */
class __TwigTemplate_6bec7e5b7b17de396cb034be8848ddbe extends \Twig\Template
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
        $this->loadTemplate("@helloworld/tab-template.html.twig", "modules/custom/helloworld/templates/contact-template.html.twig", 1)->display($context);
        // line 2
        echo "
<!-- お問い合わせフォーム -->
<form action=\"";
        // line 4
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getPath("helloworld.contact"));
        echo "\" method=\"post\">
  <!-- 名前入力欄 -->
  <div>
    <label for=\"name\">名前:</label>
    <input type=\"text\" id=\"name\" name=\"name\" required>
  </div>
  <!-- メールアドレス入力欄 -->
  <div>
    <label for=\"email\">メールアドレス:</label>
    <input type=\"email\" id=\"email\" name=\"email\" required>
  </div>
  <!-- 電話番号入力欄 -->
  <div>
    <label for=\"phone\">電話番号:</label>
    <input type=\"text\" id=\"phone\" name=\"phone\" required>
  </div>
  <!-- メッセージ入力欄 -->
  <div>
    <label for=\"message\">メッセージ:</label>
    <textarea id=\"message\" name=\"message\" required></textarea>
  </div>
  <!-- 送信ボタン -->
  <button type=\"submit\">送信</button>
</form>";
    }

    public function getTemplateName()
    {
        return "modules/custom/helloworld/templates/contact-template.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 4,  41 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/helloworld/templates/contact-template.html.twig", "/var/www/html/web/modules/custom/helloworld/templates/contact-template.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("include" => 1);
        static $filters = array();
        static $functions = array("path" => 4);

        try {
            $this->sandbox->checkSecurity(
                ['include'],
                [],
                ['path']
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
