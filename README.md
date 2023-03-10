# π€ Statamic Bard AI Writer (OpenAI GPT-3) π€

This Statamic package utilizing OpenAI's text generation technology GPT-3 allows for the generation of high-quality, human-like content within the Statamic Bard fieldtype. This integration streamlines the content creation process, saving users time and effort while still delivering professional and engaging results.

## Install π§

1. Install the addon using `composer require zsoltjanes/statamic-bard-openai`
2. Generate an API key (OPENAI_API_KEY and OPENAI_ORGANIZATION) and add it to the .env file. You can generate the key at [https://beta.openai.com/account/api-keys](https://beta.openai.com/account/api-keys).
3. Override the parameters for OpenAI in the `config/statamic-bard-openai.php`. You can find the documentation at [https://beta.openai.com/docs/api-reference/completions](https://beta.openai.com/docs/api-reference/completions).
4. You are abble to override the openai default parameters, to do that publish the config file: `php artisan vendor:publish --tag=statamic-bard-openai-config`.
5. Enjoy! π

## Feature request, Bug, issues report π

Please do not hesitate to reach out to us if you require additional features. If you experience any bugs or problems, kindly report them to us: [email](mailto:zsolt.janes@gmail.com)

## Roadmap πΊοΈ

- [ ] Image generation about given words/sentence
- [ ] Content translation
- [ ] Add translation to navigation dropdown

## License π

This addon must be licensed for use on a live website. You can acquire a license at https://statamic.com/addons/zsoltjanes/ai-writer-for-bard.
However, during the development process on your local machine, you can utilize Statamic Bard AI Writer without a license.
