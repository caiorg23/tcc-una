@extends('layouts.site')

@section('title', 'Contato')

@section('content')
<div class="section" style="background: white;">
    <div class="container" style="max-width: 720px;">
        <h1>Contato</h1>
        <p>Fale conosco e agende seu serviço. Estamos prontos para atender seu veículo com cuidado e qualidade.</p>

        <form action="#" method="post" style="margin-top: 2rem;">
            <div class="form-field">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" placeholder="Seu nome" />
            </div>
            <div class="form-field">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" />
            </div>
            <div class="form-field">
                <label for="message">Mensagem</label>
                <textarea id="message" name="message" placeholder="Escreva sua mensagem"></textarea>
            </div>
            <button type="submit" class="button">Enviar</button>
        </form>
    </div>
</div>
@endsection
