<?php

public function testConnexionEchec(): void
{
    $client = static::createClient();

    $crawler = $client->request("GET", "/login");

    $form = $crawler->selectButton("Se connecter")->form([
        "email"=>"echec@echec.com",
        "password"=>"echecPassword"
    ]);
    $client->submit($form);
    $this->assertResponseRedirects("/login");
    $client->followRedirect();
    $this->assertSelectorExists(".alert.alert-danger");
}

public function testConnexionReussie(): void
{
    $client = static::createClient();

    $crawler = $client->request("GET", "/login");

    $form = $crawler->selectButton("Se connecter")->form([
        "email"=>"reussie@reussie.com",
        "password"=>"reussiePassword"
    ]);
    $client->submit($form);
    $this->assertResponseRedirects("/");
    $this->assertSelectorExists(".alert.alert-danger");
}

?>