phalswag is a component for the Phalcon framework, to validate incoming HTTP request against a swagger.json schema
the goal is to automatize creating REST APIs

example code:
```
class ArticleController extends ControllerBase

	public function getAction($slug) {

		$Article = new \Article;

		// getArticleBySlug is the operation ID in swagger.json (note path parameter $slug doesn't have to be passed)
		$Operation = $this->_getBoundSwaggerAction('getArticleBySlug', $Action);

		if (!$Operation->isValid()) {
			$Response = $this->buildResponse(false, $Operation->getValidationMessages());
		}
		else {

			// dirty check if record exists, supposing title field is mandatory so it will be set on load
			$Article->setDirtyState(\Phalcon\Mvc\Model::DIRTY_STATE_PERSISTENT);
			$Article->refresh();

			$Response = is_null($Article->title)
				? $this->buildResponse(false, ['_'=>'404'])
				: $this->buildResponse(true, $Article->toArray(null));

		}

		return $Response;

	}

```

current status: phalswag can validate single input but not structures in body. it cannot resolve $ref 's.
