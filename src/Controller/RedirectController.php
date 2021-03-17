<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Redirect;


class RedirectController extends AbstractController
{
    // TODO: move addr to config file
    private $domain = "https://enlargeyourlink.rehost.pl/";

    function generateNewRedirectText(): string
    {
        $allowed_chars = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
        $redirectText = "";
        for ($i = strlen($this->domain); $i < 2000; $i++) {
            $redirectText .= $allowed_chars[random_int(0, strlen($allowed_chars) - 1)];
        }
        return $redirectText;
    }

    function getNewUniqueRedirectText(string $url): string
    {
        while (true) {
            $enlarged = $this->generateNewRedirectText();
            $this->getDoctrine()->getRepository(Redirect::class);
            $redirect = $this->getDoctrine()->getRepository(Redirect::class)->findOneBy(['enlarged_link' => $enlarged]);
            if ($redirect == null) {
                break;
            }
        }
        $redirect = new Redirect();
        $redirect->setEnlargedLink($enlarged);
        $redirect->setRedirectUrl($url);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($redirect);
        $entityManager->flush();
        return $enlarged;
    }

    function getRedirectText(string $url): string
    {
        $redirect = $this->getDoctrine()
            ->getRepository(Redirect::class)->findOneBy(['redirect_url' => $url]);
        if ($redirect != null) {
            return $redirect->getEnlargedLink();
        }
        return $this->getNewUniqueRedirectText($url);
    }

    /**
     * @Route("/{enlarged}")
     */
    public function redirectHandler(string $enlarged): Response
    {
        $redirect = $this->getDoctrine()->getRepository(Redirect::class)->findOneBy(['enlarged_link' => $enlarged]);
        if ($redirect == null) {
            return $this->redirectToRoute('app_root_root');
        }
        return $this->redirect($redirect->getRedirectUrl(), 308);
    }

    /**
     * @Route("/redirect/new", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $req = $request->getContent();
        if ($req == null) {
            $this->redirectToRoute('app_root_root');
        }
        $destination = json_decode($req, true)['destination'];
        if (!str_starts_with($destination, "http")) {
            $destination = "http://" . $destination;
        }
        if ($destination == null) {
            $this->redirectToRoute('app_root_root');
        }
        return new Response($this->domain . $this->getRedirectText($destination));
    }
}