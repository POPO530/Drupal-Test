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
class __TwigTemplate_771a9646e2d03b26a1e564f5952685b1 extends \Twig\Template
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
        // line 2
        $this->loadTemplate("@helloworld/tab-template.html.twig", "modules/custom/helloworld/templates/contact-display-template.html.twig", 2)->display($context);
        // line 3
        echo "
";
        // line 4
        if ( !twig_test_empty(($context["variables"] ?? null))) {
            // line 5
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
            // line 17
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["variables"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["variable"]) {
                // line 18
                echo "                <tr>
                    <td>";
                // line 19
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["variable"], "name", [], "any", false, false, true, 19), 19, $this->source), "html", null, true);
                echo "</td>
                    <td>";
                // line 20
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["variable"], "email", [], "any", false, false, true, 20), 20, $this->source), "html", null, true);
                echo "</td>
                    <td>";
                // line 21
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["variable"], "phone", [], "any", false, false, true, 21), 21, $this->source), "html", null, true);
                echo "</td>
                    <td>";
                // line 22
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["variable"], "message", [], "any", false, false, true, 22), 22, $this->source), "html", null, true);
                echo "</td>
                    <td>
                        <!-- 編集リンク -->
                        <a href=\"/test/contact/edit/";
                // line 25
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["variable"], "id", [], "any", false, false, true, 25), 25, $this->source), "html", null, true);
                echo "\">Edit</a>
                        <!-- 削除リンク -->
                        <a href=\"/test/contact/delete/";
                // line 27
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["variable"], "id", [], "any", false, false, true, 27), 27, $this->source), "html", null, true);
                echo "\" onclick=\"return confirm('Are you sure?')\">Delete</a>
                    </td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['variable'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 31
            echo "        </tbody>
    </table>
";
        } else {
            // line 34
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
        return array (  105 => 34,  100 => 31,  90 => 27,  85 => 25,  79 => 22,  75 => 21,  71 => 20,  67 => 19,  64 => 18,  60 => 17,  46 => 5,  44 => 4,  41 => 3,  39 => 2,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/helloworld/templates/contact-display-template.html.twig", "/var/www/html/web/modules/custom/helloworld/templates/contact-display-template.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("include" => 2, "if" => 4, "for" => 17);
        static $filters = array("escape" => 19);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['include', 'if', 'for'],
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
