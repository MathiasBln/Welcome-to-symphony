<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Actor;
use App\Form\ProgramSearchType;
use App\Form\CategoryType;
use App\Service\Slugify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("wild/")
 */
class WildController extends AbstractController
{
    /**
     * @param Request $request
     * @Route("", name="wild_index")
     * @return Response A response instance
     */
    public function index(Request $request) :Response
    {

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs){
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );

        }


        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Séries',
            'programs' => $programs,

        ]);
    }




    /**
     * @param string $categoryName
     * @Route("category/{categoryName}", defaults={"categoryName" = null}, name="show_category")
     * @return Response
     */
    public function showByCategory(string $categoryName)
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category has been sent to find a program in program\'s table.');
        }
        $categoryName = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($categoryName)), "-")
        );
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => "DESC"],
                3
            );
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$categoryName.' category, found in program\'s table.'
            );
        }

        return $this->render('wild/category.html.twig', [
            'programs' => $program,
            'category'  => $categoryName,
        ]);
    }


    /**
     * @param string $slug The slugger
     * @Route("show/{slug}", name="wild_show")
     * @return Response
     */
    public function showByProgram(string $slug): Response
    {

        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy([
                'program' => $program,
            ]);

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
            'seasons' => $seasons,
        ]);
    }


    /**
     * @param int $id
     * @Route("season/{id}", name="wild_season")
     * @return Response
     */
    public function showBySeason(int $id): Response
    {

        if (!$id) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $id = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($id)), "-")
        );
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);
        if (!$season) {
            throw $this->createNotFoundException(
                'No program with '.$id.', found in program\'s table.'
            );
        }
        $program = $season->getProgram();
        $episode = $season->getEpisodes();
        return $this->render('wild/season.html.twig', [
            'program' => $program,
            'seasons' => $season,
            'episodes' => $episode,
        ]);
    }


    /**
     * @param Episode $episode
     * @Route("episode/{episode}", name="wild_episode")
     * @return Response
     */
    public function showEpisode(Episode $episode): Response
    {

        $synopsis = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->find(['id' => $episode]);

        $season = $episode->getSeason();
        $program = $season ->getProgram();
        return $this->render('wild/episode.html.twig', [
            'episode' => $synopsis,
            'season' => $season,
            'program' => $program,
        ]);
    }


    /**
     * @param Actor $actor
     * @Route("actor/{id}", name="wild_actor")
     * @return Response
     */
    public function showActor(Actor $actor): Response
    {
        $program = $actor ->getPrograms();

        return $this->render('wild/actor.html.twig', [
            'actor' => $actor,
            'programs' => $program,
        ]);
    }

}
