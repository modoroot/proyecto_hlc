<?php

class Paginator
{
    private $_conn; //objeto de conexión
    private $_limit; //número total de registros por página
    private $_page; //página actual
    private $_query; //consulta
    private $_total; //número total de registros

    public function __construct($conn, $query)
    {

        $this->_conn = $conn;
        $this->_query = $query;

        $rs = $this->_conn->query($this->_query);
        $this->_total = $rs->num_rows;

    }

    /**
     * Recuperación de resultados: paginará los datos y devolverá los resultados.
     * @param $limit
     * @param $page
     * @return stdClass
     */
    public function getData($limit = 40, $page = 1)
    {
        $this->_limit = $limit;
        $this->_page = $page;

        if ($this->_limit == 'all') {
            $query = $this->_query;
        } else {
            $query = $this->_query . " LIMIT " . (($this->_page - 1) * $this->_limit) . ", 
$this->_limit";
        }
        $rs = $this->_conn->query($query);

        while ($row = $rs->fetch_assoc()) {
            $results[] = $row;
        }
//se crea un objeto de una clase estándar sobre la que se definen
//las propiedades de paginación
        $result = new stdClass();
        $result->page = $this->_page;
        $result->limit = $this->_limit;
        $result->total = $this->_total;
        if(isset($results))
        $result->data = $results;

        return $result;
    }

    public function createLinks($links, $list_class)
    {
        /**
         * Primero evaluamos si el usuario requiere una cantidad dada de enlaces o todos,
         * en el segundo caso simplemente devolvemos una cadena vacía, ya que no se requiere paginación
         */
        if ($this->_limit == 'all') {
            return '';
        }
        /**
         * Después de esto, calculamos la última página en función de la cantidad total
         * de filas disponibles y los elementos necesarios por página.
         */
        $last = ceil($this->_total / $this->_limit);
        /**
         * Luego tomamos el parámetro de enlaces que representa el número de enlaces para mostrar debajo
         * y encima de la página actual, y calculamos el enlace de inicio y final para crear.
         */
        $start = (($this->_page - $links) > 0) ? $this->_page -
            $links : 1;
        $end = (($this->_page + $links) < $last) ? $this->_page +
            $links : $last;
        /**
         * Ahora creamos la etiqueta de apertura para la lista y establecemos su clase con el parámetro de
         * clase de lista y agregamos el enlace de "página anterior", tened en cuenta que para este enlace
         * comprobamos si la página actual es la primera, y si es así, establecemos la propiedad deshabilitada
         * del enlace.
         */
        $html = '<ul class="' . $list_class . '">';

        $class = ($this->_page == 1) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="?limit=' .
            $this->_limit . '&page=' . ($this->_page - 1) . '">&laquo;</a></li>';

        /**
         * En este punto, mostramos un enlace a la primera página y un símbolo de puntos suspensivos
         * en caso de que el enlace de inicio no sea el primero.
         */
        if ($start > 1) {
            $html .= '<li><a href="?limit=' . $this->_limit .
                '&page=1"> 1 </a></li>';
            $html .= '<li class="disabled"><span>...</span></li>';
        }
        /**
         * A continuación, agregamos los enlaces debajo y encima de la página actual basada en los parámetros
         * de inicio y fin calculados previamente, en cada paso evaluamos la página actual nuevamente la
         * página de enlace mostrada y configuramos la clase activa en consecuencia.
         */
        for ($i = $start; $i <= $end; $i++) {
            $class = ($this->_page == $i) ? "active" : "";
            $html .= '<li class="' . $class . '"><a href="?limit=' .
                $this->_limit . '&page=' . $i . '">' . $i . ' . ' . '</a></li>';
        }
        /**
         * Después de esto, mostramos otro símbolo de puntos suspensivos y el enlace a la última página
         * en caso de que esta no sea la última.
         */
        if ($end < $last) {
            $html .= '<li class="disabled"><span>...</span></li>';
            $html .= '<li><a href="?limit=' . $this->_limit .
                '&page=' . $last . '">' . $last . '</a></li>';
        }
        /**
         * Finalmente, mostramos el enlace "siguiente página" y configuramos el estado deshabilitado cuando
         * el usuario está viendo la última página, cerramos la lista y devolvemos la cadena HTML generada.
         */
        $class = ($this->_page == $last) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="?limit=' .
            $this->_limit . '&page=' . ($this->_page + 1) . '">&raquo;</a></li>';

        $html .= '</ul>';

        return $html;
    }

}