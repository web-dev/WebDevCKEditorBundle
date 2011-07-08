<?php namespace WebDev\CKEditorBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Bundle\TwigBundle\TwigEngine;
use WebDev\CKEditorBundle\CKEditor\InstanceManager;

/**
 * CK Editor injects the CK Editor when it is needed.
 *
 * The onKernelResponse method must be connected to the kernel.response event.
 *
 * @author Josiah <josiah@web-dev.com.au>
 */
class CKEditorListener
{
    /**
     * Twig Templating Engine
     * 
     * @var Symfony\Bundle\TwigBundle\TwigEngine
     */
    protected $twig;

    /**
     * CKEditor Instance Manager
     *
     * @var WebDev\CKEditorBundle\CKEditor\InstanceManager
     */
    protected $instanceManager;

    protected $interceptRedirects;
    protected $mode;

    public function __construct(TwigEngine $twig, InstanceManager $instanceManager)
    {
        $this->twig = $twig;
        $this->instanceManager = $instanceManager;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $response = $event->getResponse();
        $request = $event->getRequest();

        // 
        if ($request->isXmlHttpRequest())
        {
            return;
        }

        // Don't render the editor if there aren't any instances or the content type is not html
        if( $request->isXmlHttpRequest()
            || !$this->instanceManager->hasInstances()
            || ($response->headers->has('Content-Type')
                && strpos($response->headers->get('Content-Type'), 'html') === false))
        {
            return;
        }


        $this->injectEditor($response);
    }

    /**
     * Injects and configures the CK editor for the given Response.
     *
     * @param Response $response A Response instance
     */
    protected function injectEditor(Response $response)
    {
        if (function_exists('mb_stripos')) {
            $posrFunction = 'mb_strripos';
            $substrFunction = 'mb_substr';
        } else {
            $posrFunction = 'strripos';
            $substrFunction = 'substr';
        }

        $content = $response->getContent();

        if (false !== $pos = $posrFunction($content, '</body>')) {
            $editor = "\n".str_replace("\n", '', $this->twig->render(
                'CKEditorBundle::editor.html.twig',
                array('instances' => $this->instanceManager->getInstances())
            ))."\n";
            $content = $substrFunction($content, 0, $pos).$editor.$substrFunction($content, $pos);
            $response->setContent($content);
        }
    }
}
