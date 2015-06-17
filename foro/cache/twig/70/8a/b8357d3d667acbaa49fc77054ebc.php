<?php

/* navbar_footer.html */
class __TwigTemplate_708ab8357d3d667acbaa49fc77054ebc extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"navbar\" role=\"navigation\">
\t<div class=\"inner\">

\t<ul id=\"nav-footer\" class=\"linklist bulletin\" role=\"menubar\">
\t\t<li class=\"small-icon icon-home breadcrumbs\">
\t\t\t";
        // line 6
        if ((isset($context["U_SITE_HOME"]) ? $context["U_SITE_HOME"] : null)) {
            echo "<span class=\"crumb\"><a href=\"";
            echo (isset($context["U_SITE_HOME"]) ? $context["U_SITE_HOME"] : null);
            echo "\" data-navbar-reference=\"home\">";
            echo $this->env->getExtension('phpbb')->lang("SITE_HOME");
            echo "</a></span>";
        }
        // line 7
        echo "\t\t\t";
        // line 8
        echo "\t\t\t<span class=\"crumb\"><a href=\"";
        echo (isset($context["U_INDEX"]) ? $context["U_INDEX"] : null);
        echo "\" data-navbar-reference=\"index\">";
        echo $this->env->getExtension('phpbb')->lang("INDEX");
        echo "</a></span>
\t\t\t";
        // line 9
        // line 10
        echo "\t\t</li>
\t\t";
        // line 11
        if (((isset($context["U_WATCH_FORUM_LINK"]) ? $context["U_WATCH_FORUM_LINK"] : null) && (!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null)))) {
            echo "<li class=\"small-icon icon-";
            if ((isset($context["S_WATCHING_FORUM"]) ? $context["S_WATCHING_FORUM"] : null)) {
                echo "unsubscribe";
            } else {
                echo "subscribe";
            }
            echo "\" data-last-responsive=\"true\"><a href=\"";
            echo (isset($context["U_WATCH_FORUM_LINK"]) ? $context["U_WATCH_FORUM_LINK"] : null);
            echo "\" title=\"";
            echo (isset($context["S_WATCH_FORUM_TITLE"]) ? $context["S_WATCH_FORUM_TITLE"] : null);
            echo "\" data-ajax=\"toggle_link\" data-toggle-class=\"small-icon icon-";
            if ((!(isset($context["S_WATCHING_FORUM"]) ? $context["S_WATCHING_FORUM"] : null))) {
                echo "unsubscribe";
            } else {
                echo "subscribe";
            }
            echo "\" data-toggle-text=\"";
            echo (isset($context["S_WATCH_FORUM_TOGGLE"]) ? $context["S_WATCH_FORUM_TOGGLE"] : null);
            echo "\" data-toggle-url=\"";
            echo (isset($context["U_WATCH_FORUM_TOGGLE"]) ? $context["U_WATCH_FORUM_TOGGLE"] : null);
            echo "\">";
            echo (isset($context["S_WATCH_FORUM_TITLE"]) ? $context["S_WATCH_FORUM_TITLE"] : null);
            echo "</a></li>";
        }
        // line 12
        echo "
\t\t";
        // line 13
        // line 14
        echo "\t\t<li class=\"rightside\">";
        echo (isset($context["S_TIMEZONE"]) ? $context["S_TIMEZONE"] : null);
        echo "</li>
\t\t";
        // line 15
        // line 16
        echo "\t\t";
        if ((!(isset($context["S_IS_BOT"]) ? $context["S_IS_BOT"] : null))) {
            // line 17
            echo "\t\t\t<li class=\"small-icon icon-delete-cookies rightside\"><a href=\"";
            echo (isset($context["U_DELETE_COOKIES"]) ? $context["U_DELETE_COOKIES"] : null);
            echo "\" data-ajax=\"true\" data-refresh=\"true\" role=\"menuitem\">";
            echo $this->env->getExtension('phpbb')->lang("DELETE_COOKIES");
            echo "</a></li>
\t\t\t";
            // line 18
            if ((isset($context["S_DISPLAY_MEMBERLIST"]) ? $context["S_DISPLAY_MEMBERLIST"] : null)) {
                echo "<li class=\"small-icon icon-members rightside\" data-last-responsive=\"true\"><a href=\"";
                echo (isset($context["U_MEMBERLIST"]) ? $context["U_MEMBERLIST"] : null);
                echo "\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("MEMBERLIST_EXPLAIN");
                echo "\" role=\"menuitem\">";
                echo $this->env->getExtension('phpbb')->lang("MEMBERLIST");
                echo "</a></li>";
            }
            // line 19
            echo "\t\t";
        }
        // line 20
        echo "\t\t";
        // line 21
        echo "\t\t";
        if ((isset($context["U_TEAM"]) ? $context["U_TEAM"] : null)) {
            echo "<li class=\"small-icon icon-team rightside\" data-last-responsive=\"true\"><a href=\"";
            echo (isset($context["U_TEAM"]) ? $context["U_TEAM"] : null);
            echo "\" role=\"menuitem\">";
            echo $this->env->getExtension('phpbb')->lang("THE_TEAM");
            echo "</a></li>";
        }
        // line 22
        echo "\t\t";
        // line 23
        echo "\t\t";
        if ((isset($context["U_CONTACT_US"]) ? $context["U_CONTACT_US"] : null)) {
            echo "<li class=\"small-icon icon-contact rightside\" data-last-responsive=\"true\"><a href=\"";
            echo (isset($context["U_CONTACT_US"]) ? $context["U_CONTACT_US"] : null);
            echo "\" role=\"menuitem\">";
            echo $this->env->getExtension('phpbb')->lang("CONTACT_US");
            echo "</a></li>";
        }
        // line 24
        echo "\t</ul>

\t</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "navbar_footer.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  128 => 24,  119 => 23,  103 => 19,  86 => 17,  83 => 16,  77 => 14,  76 => 13,  191 => 49,  174 => 47,  133 => 42,  121 => 40,  113 => 37,  78 => 18,  65 => 15,  59 => 14,  47 => 11,  42 => 8,  21 => 2,  376 => 103,  372 => 101,  366 => 99,  363 => 98,  356 => 93,  354 => 92,  351 => 91,  347 => 89,  336 => 84,  318 => 81,  312 => 80,  309 => 79,  299 => 78,  295 => 76,  280 => 73,  272 => 71,  270 => 70,  247 => 65,  245 => 64,  237 => 60,  228 => 58,  214 => 54,  206 => 51,  204 => 50,  198 => 49,  167 => 46,  162 => 44,  129 => 37,  111 => 36,  98 => 33,  93 => 18,  92 => 31,  89 => 30,  85 => 28,  80 => 26,  67 => 19,  51 => 17,  44 => 10,  41 => 11,  37 => 9,  27 => 3,  160 => 39,  145 => 40,  138 => 39,  125 => 41,  112 => 28,  108 => 21,  82 => 15,  68 => 21,  52 => 12,  46 => 10,  38 => 9,  36 => 8,  30 => 7,  25 => 4,  416 => 111,  406 => 109,  404 => 108,  401 => 107,  400 => 106,  397 => 105,  395 => 104,  389 => 103,  388 => 102,  375 => 101,  360 => 98,  358 => 97,  348 => 90,  345 => 95,  340 => 86,  335 => 90,  332 => 89,  320 => 85,  311 => 84,  304 => 81,  292 => 80,  284 => 79,  279 => 78,  261 => 72,  257 => 70,  217 => 55,  196 => 51,  193 => 51,  192 => 50,  183 => 48,  180 => 47,  169 => 46,  158 => 45,  157 => 44,  142 => 34,  141 => 42,  135 => 38,  134 => 37,  127 => 34,  118 => 29,  110 => 32,  107 => 31,  105 => 26,  102 => 34,  88 => 25,  81 => 24,  74 => 16,  69 => 21,  61 => 19,  50 => 16,  45 => 14,  43 => 9,  40 => 10,  39 => 11,  32 => 7,  26 => 6,  386 => 104,  383 => 103,  373 => 100,  369 => 99,  367 => 96,  362 => 97,  361 => 92,  357 => 90,  344 => 88,  343 => 87,  338 => 85,  330 => 88,  322 => 86,  316 => 78,  308 => 83,  303 => 75,  300 => 74,  296 => 72,  290 => 69,  286 => 68,  282 => 67,  255 => 57,  246 => 66,  242 => 64,  241 => 62,  236 => 50,  233 => 49,  232 => 61,  220 => 58,  212 => 53,  210 => 40,  207 => 39,  199 => 37,  197 => 52,  190 => 34,  186 => 33,  181 => 32,  177 => 48,  171 => 30,  155 => 44,  153 => 42,  150 => 41,  117 => 22,  106 => 20,  95 => 27,  84 => 23,  73 => 12,  62 => 10,  35 => 7,  22 => 2,  301 => 74,  298 => 73,  297 => 77,  294 => 71,  289 => 68,  288 => 75,  277 => 66,  276 => 77,  271 => 63,  268 => 62,  266 => 74,  263 => 68,  258 => 71,  256 => 67,  230 => 55,  229 => 54,  224 => 60,  221 => 59,  219 => 56,  216 => 49,  211 => 46,  209 => 52,  200 => 44,  189 => 43,  188 => 42,  185 => 49,  173 => 46,  170 => 45,  168 => 38,  165 => 45,  164 => 36,  161 => 22,  154 => 31,  149 => 30,  143 => 28,  140 => 43,  132 => 38,  130 => 35,  123 => 23,  116 => 22,  101 => 20,  96 => 25,  94 => 23,  91 => 17,  90 => 16,  87 => 29,  75 => 14,  72 => 13,  71 => 20,  63 => 18,  60 => 9,  58 => 18,  57 => 13,  54 => 16,  48 => 15,  34 => 7,  31 => 6,  19 => 1,);
    }
}
