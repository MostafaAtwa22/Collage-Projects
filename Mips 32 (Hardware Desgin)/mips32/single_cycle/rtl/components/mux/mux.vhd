-- mux_2x1 n_bit input/output

library ieee;
use ieee.std_logic_1164.all;
entity mux is 
    generic(
        WIDTH : integer := 32   
    ); 
    port(
        in1,in2 : in std_logic_vector(WIDTH-1 downto 0);
        sel : in std_logic;
        o : out std_logic_vector(WIDTH-1 downto 0)
    );
end entity;

architecture rtl of mux is 
    begin 
        process(sel,in1,in2) is 
            begin
                if(sel = '0') then 
                    o <= in1;
                else
                    o <= in2;
                end if;
            end process;
end architecture;