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

/* modules/custom/helloworld/templates/contact-display-template.html.twig */
class __TwigTemplate_56b1a3744151ae19c1b79e70017911d5 extends \Twig\Template
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
        if ( !twig_test_empty(($context["contacts"] ?? null))) {
            // line 2
            echo "    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Message</th>
                <th>Actions</th> <!-- アクション列を追加 -->
            </tr>
        </thead>
        <tbody>
            ";
            // line 13
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["contacts"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["contact"]) {
                // line 14
                echo "                <tr>
                    <td>";
                // line 15
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["contact"], "name", [], "any", false, false, true, 15), 15, $this->source), "html", null, true);
                echo "</td>
                    <td>";
                // line 16
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["contact"], "email", [], "any", false, false, true, 16), 16, $this->source), "html", null, true);
                echo "</td>
                    <td>";
                // line 17
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["contact"], "phone", [], "any", false, false, true, 17), 17, $this->source), "html", null, true);
                echo "</td>
                    <td>";
                // line 18
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["contact"], "message", [], "any", false, false, true, 18), 18, $this->source), "html", null, true);
                echo "</td>
                    <td>
                        <!-- 編集リンク -->
                        <a href=\"/test/contact/edit/";
                // line 21
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["contact"], "id", [], "any", false, false, true, 21), 21, $this->source), "html", null, true);
                echo "\">Edit</a>
                        <!-- 削除リンク -->
                        <a href=\"/test/contact/delete/";
                // line 23
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["contact"], "id", [], "any", false, false, true, 23), 23, $this->source), "html", null, true);
                echo "\" onclick=\"return confirm('Are you sure?')\">Delete</a>
                    </td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['contact'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 27
            echo "        </tbody>
    </table>
";
        } else {
            // line 30
            echo "    <p>No contacts found.</p>
";
        }
    }

    public function getTemplateName()
    {
        return "modules/custom/helloworld/templates/contact-display-template.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  99 => 30,  94 => 27,  84 => 23,  79 => 21,  73 => 18,  69 => 17,  65 => 16,  61 => 15,  58 => 14,  54 => 13,  41 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/helloworld/templates/contact-display-template.html.twig", "/var/www/html/web/modules/custom/helloworld/templates/contact-display-template.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 1, "for" => 13);
        static $filters = array("escape" => 15);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['escape'],
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
