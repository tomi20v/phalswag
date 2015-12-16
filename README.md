phalswag is a component for the Phalcon framework, to validate incoming HTTP
request against a swagger.json schema and also build the corresponding response
the goal is to automate writing REST APIs as much as possible based on swagger
definitions

simplest example using the supplied Phalcon Mvc Controller extension:
```
class UsersController extends Controller
{
	// by defining these,
	protected static $_swaggerPath = '../app/config/swagger';
	protected static $_swaggerFname = 'users.json';

	public function getAction() {

		try {

			$Response = $this->_process(
				// swagger operation ID
				'usersGet',
				// callback which receives input and shall return result data
				function($RequestModel) {
					$User = UserModel::findById($RequestModel->id);
					return $User;
				}
			);

		}
		catch (\Exception $e) {

			$Response = $this->ResponseBuilder->buildError(500);

		}

		return $Response;

	}

```

a more complete example would include:
```
	// get the operation object
	$Operation = $this->SwaggerService->getOperationById(
		$operationId,
		$this->_Swagger
	);

	// bind to request model
	$this->SwaggerService->bindRequest(
		$RequestModel,
		$Operation,
		$this->dispatcher->getParams(),
		$this->request
	);

	// get response schema and build from data object
	$ResponseSchema = $this->SwaggerService->getResponseSchema(
		200,
		$Operation,
		$this->_Swagger
	);
	$Result = $this->SwaggerService->buildBySchema(
		$Object,
		$ResponseSchema
	);

```

current status: traversable models for most of swagger definition elements
can populate a request model (class of your choice) with data from the HTTP
input. can do basic validation of this data but eg cannot validate
structures in body. it can build responses from models containing result data.
it cannot resolve $ref 's.
