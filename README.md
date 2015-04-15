phalswag is a component for the Phalcon framework, to validate incoming HTTP request against a swagger.json schema
the goal is to automate writing REST APIs as much as possible based on swagger definitions

example code:
```
class ArticleController extends ControllerBase

	public function getAction($slug) {

		$Article = new \Article;

		// getArticleBySlug is the operation ID in swagger.json (note path parameter $slug doesn't have to be passed)
		$Operation = $this->_getBoundSwaggerAction('getArticleBySlug', $Article);

		if (!$Operation->isValid()) {
			$Response = $this->$ResponseBuilder->buildResponse(false, $Operation->getValidationMessages());
		}
		else {

			// dirty check if record exists, supposing title field is mandatory so it will be set on load
			$Article->setDirtyState(\Phalcon\Mvc\Model::DIRTY_STATE_PERSISTENT);
			$Article->refresh();

			$Response = is_null($Article->title)
				? $this->$ResponseBuilder->buildResponse(false, ['_'=>'404'])
				: $this->$ResponseBuilder->buildResponse(true, $Article->toArray(null));

		}

		return $Response;

	}

```

current status: phalswag can validate some type of input but not structures in body. it cannot resolve $ref 's.

