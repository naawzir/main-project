<fieldset class="Feedback">
    <div class="Feedback__indicator">
        <img src="/images/sad_coffee.png" alt="">
        Poor
    </div>

    @foreach(range(1, 10) as $rating)
        <input id="{{ $name }}-{{ $rating }}" name="{{ $name }}" type="radio" value="{{ $rating }}" class="sr-only">
        <label for="{{ $name }}-{{ $rating }}">{{ $rating }}</label>
    @endforeach

    <div class="Feedback__indicator">
        <img src="/images/happy_coffee.png" alt="">
        Fantastic
    </div>
</fieldset>
