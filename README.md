# 🤖 Statamic OpenAI Bard AI Writer 🤖

![Statamic 5](https://img.shields.io/badge/Statamic-5.0+-FF269E?link=https://statamic.com)
![Downloads](https://badgen.net/packagist/dt/zsoltjanes/statamic-bard-openai)

This Statamic package utilizing OpenAI's text generation technology GPT allows for the generation of high-quality, human-like content within the Statamic Bard fieldtype. This integration streamlines the content creation process, saving users time and effort while still delivering professional and engaging results.

## Install 🔧

1. Install the addon using `composer require zsoltjanes/statamic-bard-openai`
2. Publish the addon blueprints: `php artisan vendor:publish --tag=statamic-bard-openai-blueprints`
3. In the Statamic Control Panel create/edit the Globals set with handle `statamic_bard_openai` named `Bard AI Writer`.
4. Get your 🔑 OpenAI API key: https://platform.openai.com/api-keys
5. Set your OpenAI settings in that Globals set (API key, organization, model (selectable models can be found here: https://platform.openai.com/docs/models), temperature, max output tokens).
6. Edit `presets` to add/remove prompt types.
7. Enjoy! 🎉

## Upgrade guide from v1.0.3

This addon no longer uses a config file. All settings must be stored in Statamic Globals.

1. Publish the addon blueprints: `php artisan vendor:publish --tag=statamic-bard-openai-blueprints`
2. In the Statamic Control Panel create/edit the Globals set with handle `statamic_bard_openai`.
3. Move your settings into that Globals set:
   - `api_key`
   - `organization` (optional)
   - `model`
   - `temperature`
   - `max_output_tokens`
   - `presets` (grid, can be extended with new prompt types; modes: replace/append/prepend)

## Feature request, Bug, issues report 🐛

Please do not hesitate to reach out to us if you require additional features. If you experience any bugs or problems, kindly report them to us: [email](mailto:zsolt.janes@gmail.com)

## License 📝

This addon must be licensed for use on a live website. You can acquire a license at [https://statamic.com/addons/zsoltjanes/ai-writer-for-bard](https://statamic.com/addons/zsoltjanes/ai-writer-for-bard).
However, during the development process on your local machine, you can utilize Statamic Bard AI Writer without a license.
