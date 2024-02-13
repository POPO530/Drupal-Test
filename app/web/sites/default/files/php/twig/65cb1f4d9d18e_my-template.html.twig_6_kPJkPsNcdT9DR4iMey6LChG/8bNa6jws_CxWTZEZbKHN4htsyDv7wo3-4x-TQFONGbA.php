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
        // line 3
        echo "
";
        // line 5
        $this->loadTemplate("@helloworld/tab-template.html.twig", "modules/custom/helloworld/templates/my-template.html.twig", 5)->display($context);
        // line 6
        echo "
";
        // line 8
        echo "<p>Test twig template!</p>

";
        // line 10
        if ( !twig_test_empty(($context["variables"] ?? null))) {
            // line 11
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
            // line 23
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["variables"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["variable"]) {
                // line 24
                echo "                <tr>
                    <td>";
                // line 25
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["variable"], "name", [], "any", false, false, true, 25), 25, $this->source), "html", null, true);
                echo "</td>
                    <td>";
                // line 26
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["variable"], "email", [], "any", false, false, true, 26), 26, $this->source), "html", null, true);
                echo "</td>
                    <td>";
                // line 27
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["variable"], "phone", [], "any", false, false, true, 27), 27, $this->source), "html", null, true);
                echo "</td>
                    <td>";
                // line 28
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["variable"], "message", [], "any", false, false, true, 28), 28, $this->source), "html", null, true);
                echo "</td>
                    <td>
                        <!-- 削除リンク -->
                        <a href=\"/test/contact/delete2/";
                // line 31
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, $context["variable"], "id", [], "any", false, false, true, 31), 31, $this->source), "html", null, true);
                echo "\" onclick=\"return confirm('Are you sure?')\">Delete</a>
                    </td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['variable'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 35
            echo "        </tbody>
    </table>
";
        } else {
            // line 38
            echo "    <p>No contacts found.</p>
";
        }
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
        return array (  107 => 38,  102 => 35,  92 => 31,  86 => 28,  82 => 27,  78 => 26,  74 => 25,  71 => 24,  67 => 23,  53 => 11,  51 => 10,  47 => 8,  44 => 6,  42 => 5,  39 => 3,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/helloworld/templates/my-template.html.twig", "/var/www/html/web/modules/custom/helloworld/templates/my-template.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("include" => 5, "if" => 10, "for" => 23);
        static $filters = array("escape" => 25);
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
