<?php
class UndoRedoStack
{
    //UndoRedoStack uses the Memento Design Pattern
    private $history;
    private $undoRedoPointer;
    
    public function __construct()
    {
        $this->history = [];
        $this->undoRedoPointer = -1;
    }
    
    public function execute($action_data)
    {
        // discard any previously undone actions and add the new action to the array
        if ($this->undoRedoPointer == -1) {
            $this->history = [$action_data];
            $this->undoRedoPointer = 0;
         } else {
            $this->history = array_slice($this->history, 0, ($this->undoRedoPointer+1));
            $this->history[] = $action_data;
            $this->undoRedoPointer++;
        }
    }
    
    public function undo()
    {
        // Try to undo the last action, handle errors as you go
        if ($this->undoRedoPointer >= 0) {
            $action_data = $this->history[$this->undoRedoPointer];
        } else {
            throw new UnderflowException('There are no operations to undo');
        }
        // if successful, move back one place in the history
        if ($this->undoRedoPointer <= 0) {
            $this->undoRedoPointer = -1;
        } else {
            $this->undoRedoPointer--;
        }

        return $action_data;
    }
    
    public function redo()
    {
        // Try to redo the next action, handle errors as you go
        if (count($this->history) > ($this->undoRedoPointer+1) || count($this->history) > 0 && ($this->undoRedoPointer == -1)) {
            $action_data = $this->history[$this->undoRedoPointer+1];
        } else {
            throw new UnderflowException('There are no operations to redo');
        }
        // if successful, move forward one place in the history
        if ($this->undoRedoPointer < 0) {
            $this->undoRedoPointer = 0;
        } else {
            $this->undoRedoPointer++;
        }
 
        return $action_data;
    }
    
    public function can_undo()
    {
        $can = false;
        if ($this->undoRedoPointer >= 0) {
            $can = true;
        }
        return $can;
    }
    
    public function can_redo()
    {
        $can = false;
        if (count($this->history) > ($this->undoRedoPointer+1) || count($this->history) > 0 && ($this->undoRedoPointer == -1)) {
            $can = true;
        }
        return $can;
    }
    public function showstat()
    {
        echo "Pos = " . $this->undoRedoPointer . " history: ";
        print_r($this->history);
    }
     
}