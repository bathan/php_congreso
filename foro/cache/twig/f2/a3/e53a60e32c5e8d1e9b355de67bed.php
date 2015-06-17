<?php

/* user_activate_passwd.txt */
class __TwigTemplate_f2a3e53a60e32c5e8d1e9b355de67bed extends Twig_Template
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
        echo "Subject: Activación de nueva clave

Hola ";
        // line 3
        echo (isset($context["USERNAME"]) ? $context["USERNAME"] : null);
        echo "

Estás recibiendo esta notificación porque vos (o alguien reemplazándote) solicitó una nueva clave 
para tu cuenta en \"";
        // line 6
        echo (isset($context["SITENAME"]) ? $context["SITENAME"] : null);
        echo "\". Si vos no la solicitaste por favor ignora esta notificación. 
Si persiste la solicitud contactate con La Administración del Sitio.

Para usar la nueva clave necesitás activarla. Para esto visita el siguiente enlace.

";
        // line 11
        echo (isset($context["U_ACTIVATE"]) ? $context["U_ACTIVATE"] : null);
        echo "

Si no hay inconvenientes podrás identificarte mediante la siguiente nueva clave:

Clave: ";
        // line 15
        echo (isset($context["PASSWORD"]) ? $context["PASSWORD"] : null);
        echo "

Por supuesto posteriormente podés cambiar esta clave para tu cuenta mediante el Panel de Control de Usuario. 
Si tenés alguna dificultad contactate con La Administración del Sitio.

";
        // line 20
        echo (isset($context["EMAIL_SIG"]) ? $context["EMAIL_SIG"] : null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "user_activate_passwd.txt";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  52 => 20,  44 => 15,  37 => 11,  29 => 6,  23 => 3,  19 => 1,);
    }
}
