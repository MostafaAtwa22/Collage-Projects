-- n_bit_addr n_bit_data reg_file

library ieee;
use ieee.std_logic_1164.all;
use ieee.numeric_std.all;
entity reg_file is 
    generic(
        REG_WIDTH : integer := 32;
        ADR_WIDTH : integer := 5
    );
    port(
        clk_read : in std_logic := '0';
        read_reg1,read_reg2 : in std_logic_vector(ADR_WIDTH-1 downto 0) := (others => '0'); 
        
        clk_write,write_en :in std_logic := '0';
        write_reg : in std_logic_vector(ADR_WIDTH-1 downto 0) := (others => '0');
        write_data : in std_logic_vector(REG_WIDTH-1 downto 0) := (others => '0');
        
        read_data1,read_data2 : out std_logic_vector(REG_WIDTH-1 downto 0)
    );
end entity;

architecture rtl of reg_file is
    type reg_file_type is array(0 to 2**ADR_WIDTH-1) of std_logic_vector(REG_WIDTH-1 downto 0);
    signal reg_file : reg_file_type := (
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0'),
        (others => '0')
    );
    begin
        READ : process(clk_read) is
        begin
            if(rising_edge(clk_read)) then 
                read_data1 <= reg_file(to_integer(unsigned(read_reg1)));
                read_data2 <= reg_file(to_integer(unsigned(read_reg2)));
            end if;
        end process;
        WRITE : process(clk_write) is
            begin
                if(rising_edge(clk_write)) then 
                    if(write_en = '1' and to_integer(unsigned(write_reg)) /= 0) then
                        reg_file(to_integer(unsigned(write_reg))) <= write_data;
                    end if;
                end if;
        end process;
end architecture;

--  |name        |reg#    |convention           |                        
--  |$ZERO       |0       |constant 0           |                    
--  |$at         |1       |reserved for compiler|                
--  |$v0-$v1     |2-3     |results              |                
--  |$a0-$a3     |4-7     |arguments            |                    
--  |$t0-$t7     |8-15    |caller-temps         |            
--  |$s0-$s7     |16-23   |caller-saved         |    
--  |$t8-$t9     |24-25   |caller-temps         |    
--  |$k0-$k1     |26-27   |reserved for OS      |            
--  |$gp         |28      |global_pointer       |            
--  |$sp         |29      |stack_pointer        |            
--  |$fp         |30      |frame_pointer        |            
--  |$ra         |31      |return_pointer       |    