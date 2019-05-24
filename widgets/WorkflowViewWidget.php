<?php

namespace vthang87\workflow\widgets;

use vthang87\workflow\assets\WorkflowAsset;
use vthang87\workflow\models\Workflow;
use stdClass;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;

class WorkflowViewWidget extends Widget
{
    /**
     * @var mixed the Workflow object to display, or a model attached to a
     * SimpleWorkflowBehavior. In this case, if the model is in a status, its parent workflow
     * is used, otherwise the default workflow is used.
     */
    public $workflow;
    /**
     * @var string Id of the HTML element that is as a container for the
     * workflow view
     */
    public $containerId;
    /**
     * @var string Id of the VIS javascript objects instance created by this widget to render the workflow.
     * If not set a unique id is automatically created.
     */
    public $visNetworkId;
    /**
     * @var \vthang87\workflow\models\Workflow the workflow instance to display
     */
    private $_workflow;
    /**
     * @var string unique Id
     */
    private $_visId;

    private $_shape = 'box';

    /**
     * (non-PHPdoc)
     * @see \yii\base\Object::init()
     */
    public function init()
    {
        parent::init();
        if (!isset($this->containerId)) {
            throw new InvalidConfigException("Parameter 'containerId' is missing ");
        }
        if (!isset($this->visNetworkId)) {
            $this->visNetworkId = uniqid('vis_');
        }
        if (!isset($this->workflow)) {
            throw new InvalidConfigException("Parameter 'workflow' is missing ");
        }

        if ($this->workflow instanceof Workflow) {
            $this->_workflow = $this->workflow;
        }

        if ($this->_workflow == null) {
            throw new InvalidConfigException("Failed to find workflow instance from parameter 'workflow'");
        }
        $this->_visId = uniqid();
    }

    /**
     * @see \yii\base\Widget::run()
     */
    public function run()
    {
        $this->getView()->registerJs(
            $this->createJs()
        );
        WorkflowAsset::register($this->getView());
    }

    /**
     * Creates and returns the JS code.
     * The JS code is used to initialize the VIS instances in charge of displaying the workflow.
     *
     * @return string the JS code
     */
    private function createJs()
    {
        $nodes = $this->_workflow->statuses;

        $trList = [];
        $nodeList = [];

        //Start node
        $start_node = new \stdClass();
        $start_node->id = 0;
        $start_node->label = Yii::t('workflow', 'Start');
        $start_node->shape = $this->_shape;
        $start_node->margin = 8;
//		$font               = new stdClass();
//		$font->size         = 20;
//		$start_node->font   = $font;
        $start_node->color = '#FB7E81';

        $end_node = new \stdClass();
        $end_node->id = -1;
        $end_node->label = Yii::t('workflow', 'End');
        $end_node->shape = $this->_shape;
        $end_node->margin = 8;
        $end_node->color = '#FB7E81';
//		$font             = new stdClass();
//		$font->size       = 20;
//		$end_node->font   = $font;

        $nodeList[] = $start_node;

        //Start  transition
        $t = new \stdClass();
        $t->from = $start_node->id;
        $t->color = new stdClass();
        $t->color->color = '#97C2FC';
        $t->to = $this->_workflow->init_status;
        $t->arrows = 'to';

        $trList[] = $t;


        /** @var \vthang87\workflow\models\Status $node */
        foreach ($nodes as $node) {
            $n = new \stdClass();
            $n->id = $node->id_status;
            $n->label = $node->label;
            $n->shape = 'box';
            $f = new stdClass();
            $f->size = 15;
            $n->font = $f;

            $nodeList[] = $n;

            $transitions = $node->transitionFrom;
            if (count($transitions)) {
                foreach ($transitions as $transition) {
                    $t = new \stdClass();
                    $t->from = $n->id;
                    $t->to = $transition->id_status_end;
                    $t->arrows = 'to';
                    $trList[] = $t;
                }
            } else {
                $t = new \stdClass();
                $t->from = $n->id;
                $t->to = $end_node->id;
                $t->arrows = 'to';
                $trList[] = $t;
            }
        }

        $nodeList[] = $end_node;

        $jsonNodes = \yii\helpers\Json::encode($nodeList);
        $jsonTransitions = \yii\helpers\Json::encode($trList);

        $js = <<<JS
var {$this->visNetworkId} = new vis.Network(
    document.getElementById('{$this->containerId}'),
    {
        nodes: new vis.DataSet($jsonNodes),
        edges: new vis.DataSet($jsonTransitions)
    },
    {
        "physics": {
            "solver": "repulsion"
        }
    }
);
JS;
        return $js;
    }
}
