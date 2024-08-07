<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* core/themes/olivero/templates/misc/feed-icon.html.twig */
class __TwigTemplate_66c980f1776d63ae1f6ebacad36832d4 extends Template
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
        $this->sandbox = $this->env->getExtension(SandboxExtension::class);
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 14
        yield "
";
        // line 15
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("olivero/feed"), "html", null, true);
        yield "

";
        // line 21
        if (( !array_key_exists("title", $context) || (null === ($context["title"] ?? null)))) {
            // line 22
            yield "  ";
            $context["title"] = t("RSS Feed");
        }
        // line 24
        yield "
<a href=\"";
        // line 25
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["url"] ?? null), 25, $this->source), "html", null, true);
        yield "\"";
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", ["feed-icon"], "method", false, false, true, 25), 25, $this->source), "html", null, true);
        yield ">
  <span class=\"feed-icon__label\">
    ";
        // line 27
        yield $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 27, $this->source), "html", null, true);
        yield "
  </span>
  <span class=\"feed-icon__icon\" aria-hidden=\"true\">
    ";
        // line 30
        yield from         $this->loadTemplate("@olivero/../images/rss.svg", "core/themes/olivero/templates/misc/feed-icon.html.twig", 30)->unwrap()->yield($context);
        // line 31
        yield "  </span>
</a>
";
        return; yield '';
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "core/themes/olivero/templates/misc/feed-icon.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  72 => 31,  70 => 30,  64 => 27,  57 => 25,  54 => 24,  50 => 22,  48 => 21,  43 => 15,  40 => 14,);
    }

    public function getSourceContext()
    {
        return new Source("", "core/themes/olivero/templates/misc/feed-icon.html.twig", "E:\\drupal\\drupal-10.0.0\\core\\themes\\olivero\\templates\\misc\\feed-icon.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 21, "set" => 22, "include" => 30);
        static $filters = array("escape" => 15, "t" => 22);
        static $functions = array("attach_library" => 15);

        try {
            $this->sandbox->checkSecurity(
                ['if', 'set', 'include'],
                ['escape', 't'],
                ['attach_library'],
                $this->source
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
