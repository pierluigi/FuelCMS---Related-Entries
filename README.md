FuelCMS---Related-Entries
=========================

FuelCMS - Related Entries - a DRY Base_Module for related entries

## Introduction

While FuelCMS makes it actually easy to implement a one_to_many relationship between models, this comes at the cost of having to write tons of un-DRY code. 
Also, for each relationship you're required to create a modelA_to_modelB class that takes care of keeping things tight. 
Without pretending to have found the best solution, I think I've found a pretty decent way of handling such a thing without writing too many lines of code, and I thought I might as well share it with whoever might find this useful.

## Notes
This base model is meant to be used within FuelCMS's advanced modules.
The idea is to have a simple way to create simple `one_to_many` relations between models with minimal effort. 
Here's what the final result looks like: 

http://i.imgur.com/PXUzs.png

Multiple models are handled with `optgroups` in the Chosen multi-select.

A jQuery plugin called "Chosen" is used to display the multi select fields. For more info check: 
http://harvesthq.github.com/chosen/ 


## Installation
1. Copy this file inside your own advanced module's 'libaries' folder (ie `fuel/modules/your_module/libraries`)
2. Grab a copy of jQuery's 'Chosen' plugin here: http://harvesthq.github.com/chosen/ and save it inside your module's assets. 
More on how to do this below.

## Usage
All the modules involved will have to extend the `Related_entries_model` (and its record class `Related_entry_model`). 
Once that's done, all you have to do is declare an array of related_models that will be used to populate the form inside the top-level model. 

Here's an example:

File: `fuel/modules/your_module/models/Projects_model.php`

  require_once(YOUR_MODULE_PATH . 'libraries/related_entries_model.php');

    class Projects_model extends Related_entries_model
	  {
     public $related_models = array('authors_model', 'categories_model');
      (...)
    }
    class Related_entry_model extends Base_module_record
    {
      (...) // the data record doesn't do much as of yet...
    }

Where clearly the `authors_model` and `categories_model` are found in the `fuel/modules/your_module/models` folder.

## How do I use a jQuery plugin like Chosen inside my advanced module?
You have to add the JS files inside `fuel/modules/your_module/assets/js` and the css inside `fuel/modules/your_module/assets/css` 
Then you'll have to configure each module's 'js' property in order to have the plugin loaded. 
The official way to do so is to add:


    'js' => array('your_module' => ('jquery.pluginname.js', 'file2.js', 'etc.js')

inside your `your_module_fuel_models.php` file, for each one of the models declared.

Since there is no official way to load more than one CSS file besides the automatically loaded `your_module.css` file, I suggest to add the other CSS files required by the Chosen plugin at the head of the `your_module.css' file like so:

  > @import url("chosen/chosen.css");

In this case the CSS is inside `fuel/modules/your_module/assets/css/chosen` but you could put it anywhere really.


