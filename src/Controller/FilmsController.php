<?php

namespace App\Controller;

use App\Entity\Films;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FilmsController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/films', name: 'app_films')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $films = $doctrine->getRepository(Films::class)->findAll();
        return $this->render('films/films.html.twig', [
            'films' => $films,
        ]);
    }

    #[Route('/films/show/{id}', name: 'films_show', requirements: ['id' => '\d+'])]
    public function show(Films $films): Response
    {
        // Rendre la vue avec les détails du film sélectionné et ses articles
        return $this->render('films/show.html.twig', [
            'films' => $films,
            'articles' => $films->getArticles(),
        ]);
    }

    #[Route('/films/search', name: 'app_films_search')]
    public function search(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User || (!$user->hasRole('ADMIN')) && !$user->hasRole('USER')) {
            return $this->redirectToRoute('app_login');
        }

        $query = $request->query->get('query');

        $searchResults = [];
        if ($query) {
            $apiKey = $this->getParameter('tmdb_api_key');
            $response = $this->client->request('GET', "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&query=" . urlencode($query));

            $data = $response->toArray();
            $searchResults = $data['results'] ?? [];

            // Récupérer les films déjà présents dans la base de données
            $existingFilms = $doctrine->getRepository(Films::class)->findAll();
            $existingTmdbIds = array_map(function ($film) {
                return $film->getImdbId(); // Utilise `getImdbId()` si tu enregistres le `tmdb_id` ici
            }, $existingFilms);

            // Filtrer les résultats pour enlever les films déjà présents dans la base de données
            $searchResults = array_filter($searchResults, function ($result) use ($existingTmdbIds) {
                return !in_array($result['id'], $existingTmdbIds);
            });
        }

        return $this->render('films/films.html.twig', [
            'films' => $doctrine->getRepository(Films::class)->findAll(),
            'search_results' => $searchResults,
        ]);
    }

    #[Route('/films/add', name: 'app_films_add', methods: ['POST'])]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User || (!$user->hasRole('ADMIN')) && !$user->hasRole('USER')) {
            return $this->redirectToRoute('app_login');
        }

        $tmdbId = $request->request->get('tmdb_id');
        $apiKey = $this->getParameter('tmdb_api_key');

        // Fetch movie details from TMDb API
        $response = $this->client->request('GET', "https://api.themoviedb.org/3/movie/{$tmdbId}?api_key={$apiKey}");
        $movieData = $response->toArray();

        $entityManager = $doctrine->getManager();
        $film = new Films();
        $film->setOriginalTitle($movieData['title']);
        $film->setReleaseDate(new \DateTimeImmutable($movieData['release_date']));
        $film->setImdbId($movieData['id']); // Or set the TMDB ID instead of IMDb ID if that's what you want
        // $film->setPosterPath($request->request->get('posterPath'));

        // Retrieve poster path, or set it to null if not available
        $posterPath = $movieData['poster_path'] ?? null;
        $film->setPosterPath($posterPath);


        $entityManager->persist($film);
        $entityManager->flush();

        return $this->redirectToRoute('app_films');
    }
}
