<style type="text/css"> 
    div.form{
        width:400px;
        float:left;
    }
    div.form label{
        display:block;
        text-align:right;
        width:140px;
        float:left;
    }
    div.form input, div.form textarea, div.form checkbox{
        float:left;
        font-size:12px;
        padding:4px 2px;
        border:solid 1px #aacfe4;
        width:200px;
        margin:2px 0 20px 10px;
    }
</style>

<?php echo form_open('artificial_intelligence/nnga/set');?>
<div class="form">
<?php
    echo form_label('Learning Rate');
    echo form_input('nn_learning_rate', $nn_learning_rate);
    echo form_label('Neural Network\'s Maximum Epoch');
    echo form_input('nn_max_loop', $nn_max_loop);
    echo form_label('Maximum Means Square Error');
    echo form_input('nn_max_mse', $nn_max_mse);
    echo form_label('Hidden Neuron Count');
    echo form_input('nn_hidden_neuron_count', $nn_hidden_neuron_count);
    echo form_label('Training Dataset');
    echo form_textarea('nn_dataset', $nn_dataset);
?>
</div>
<div class="form">
<?php
    echo form_label('GA\'s Maximum Epoch');
    echo form_input('ga_max_loop', $ga_max_loop);
    echo form_label('GA\'s Individu/Population Count');
    echo form_input('ga_individu_count', $ga_individu_count);
    echo form_label('Elitism Rate');
    echo form_input('ga_elitism_rate', $ga_elitism_rate);
    echo form_label('Mutation Rate');
    echo form_input('ga_mutation_rate', $ga_mutation_rate);
    echo form_label('Crossover Rate');
    echo form_input('ga_crossover_rate', $ga_crossover_rate);
    echo form_label('Reproduction Rate');
    echo form_input('ga_reproduction_rate', $ga_reproduction_rate);
    echo form_submit('set', 'SET');
?>
</div>
<?php echo form_close();?>
